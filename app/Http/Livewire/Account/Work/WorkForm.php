<?php

namespace App\Http\Livewire\Account\Work;

use App\Models\Collection;
use App\Models\own_books_works;
use App\Models\Participation;
use App\Models\Participation_work;
use App\Models\Printorder;
use App\Models\Work;
use App\Models\work_topic;
use App\Models\work_type;
use App\Rules\SameParticipation;
use App\Service\WorkStatService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;
use Livewire\Component;
use Livewire\WithFileUploads;
use Spatie\Image\Image;
use Spatie\LaravelImageOptimizer\Facades\ImageOptimizer;

class WorkForm extends Component
{
    use WithFileUploads;

    public $form_type;

    public $work_title;
    public $work_text;
    public $file;
    public $filepreview;
    public $file_name;
    public $file_extension;
    public $work_type;
    public $work_topic;
    public $work_topics;
    public $work_types;
    public $cropped_img = "";

    public $back_after_work_adding;

    protected $listeners = ['storeWork'];

    public function render()
    {

        if ($this->file ?? 0 != null && $this->filepreview == null) {
            if (file_exists(storage_path('app/livewire-tmp/' . $this->file->getfilename()))) {
                $file_old_temp_path = storage_path('app/livewire-tmp/' . $this->file->getfilename());
                $file_new_temp_path = public_path('filepond_temp/work_pics/' . $this->file->getfilename());
                $this->file_name = $this->file->getfilename();
                $this->file_extension = $this->file->extension();
                File::move($file_old_temp_path, $file_new_temp_path); // перемещаем в нашу временную папку
                $this->filepreview = '/filepond_temp/work_pics/' . $this->file->getfilename();
                $this->dispatchBrowserEvent('update_preview');
            }
        }

        $this->work_types = work_type::orderBy('name', 'asc')->get();
        $this->work_topics = work_topic::orderBy('name', 'asc')->get();


        return view('livewire.account.work.work-form');

    }

    public function mount(Request $request, $form_type, $work_id)
    {
        $this->back_after_work_adding = $request->session()->get('back_after_work_adding');
        $this->form_type = $form_type;

        if ($form_type == 'edit') {
            $this->work = Work::where('id', $work_id)->first();
            $this->work_title = $this->work['title'];
            $this->work_text = $this->work['text'];
            $this->work_type = $this->work['work_type_id'];
            $this->work_topic = $this->work['work_topic_id'];
            $this->filepreview = $this->work['picture_cropped'];
        } else {
            $this->work_type = null;
            $this->work_topic = null;
        }

    }

    public function storeWork(WorkStatService $work_stat)
    {


        // --------- Ищем ошибки в заполнении  --------- //
        $errors_array = [];

        if ($this->work_title == null) {
            array_push($errors_array, 'Введите название произведения!');
        }

        if ($this->work_text == null) {
            array_push($errors_array, 'Введите текст произведения!');
        }

        if ($this->work_type == null) {
            array_push($errors_array, 'Выберите тип произведения!');
        }

        if ($this->work_topic == null) {
            array_push($errors_array, 'Выберите тему произведения!');
        }

        if ($this->form_type == 'edit') {
            $work_in_collections = Participation_work::where('work_id', $this->work['id'])->get() ?? 0;
            $work_in_own_book = own_books_works::where('work_id', $this->work['id'])->get() ?? 0;

            if (count($work_in_collections) > 0) {
                array_push($errors_array, 'Это произведение используется в сборнике! Его нельзя изменить/удалить сейчас.');
            }

            if (count($work_in_own_book) > 0) {
                array_push($errors_array, 'Это произведение используется в собственной книге! Его нельзя удалить сейчас.');
            }
        }

        if (!empty($errors_array)) {
            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'error',
                'title' => 'Что-то пошло не так!',
                'text' => implode("<br>", $errors_array),
            ]);
        }

        // --------- //Ищем ошибки в заполнении  --------- //

        if (empty($errors_array)) {


            $work_stat_response = $work_stat->calculate($this->work_text);

            if ($this->form_type == 'create') {
                $this->work = new Work();
                $this->work->title = $this->work_title;
                $this->work->text = $this->work_text;
                $this->work->symbols = $work_stat_response['symbols'];
                $this->work->rows = $work_stat_response['rows'];
                $this->work->pages = $work_stat_response['pages'];
                $this->work->upload_type = 'вручную';
                $this->work->user_id = Auth::user()->id;
                $this->work->work_type_id = $this->work_type;
                $this->work->work_topic_id = $this->work_topic;

                $this->work->save();
            } else {
                $this->work->update([
                    'title' => $this->work_title,
                    'text' => $this->work_text,
                    'symbols' => $work_stat_response['symbols'],
                    'rows' => $work_stat_response['rows'],
                    'pages' => $work_stat_response['pages'],
                    'work_type_id' => $this->work_type,
                    'work_topic_id' => $this->work_topic
                ]);

                if ($this->work['picture'] && !$this->filepreview) { // Если удалили картинку
                    File::delete(public_path($this->work['picture']));
                    File::delete(public_path($this->work['picture_cropped']));
                    $this->work->update([
                        'picture' => null,
                        'picture_cropped' => null
                    ]);
                }
            }


            // Если есть изображение: оптимизируем его и уменьшаем

            // Большая картинка
            if ($this->file ?? 0 != null) {
                $file_old_temp_path = public_path('filepond_temp/work_pics/' . $this->file_name);
                $cur_width = Image::load($file_old_temp_path)->getWidth();
                if ($cur_width > 600) {
                    Image::load($file_old_temp_path)
                        ->width(600)
                        ->optimize()
                        ->save($file_old_temp_path);
                }
                $file_new_path = public_path('img/work_pics/work_' . $this->work['id'] . '.' . $this->file_extension);
                File::move($file_old_temp_path, $file_new_path); // перемещаем в нашу временную папку
                $picture = '/img/work_pics/work_' . $this->work['id'] . '.' . $this->file_extension;
                File::delete(public_path($file_old_temp_path));
            } else {
                $picture = null;
            }


            if ($this->file ?? 0 != null) {
                // Обрезанная картинка
                $folderPath_cropped = public_path('img/work_pics/');
                $image_parts_cropped = explode(";base64,", $this->cropped_img);
                $image_type_aux_cropped = explode("image/", $image_parts_cropped[0]);
                $image_type_cropped = $image_type_aux_cropped[1];
                $image_base64 = base64_decode($image_parts_cropped[1]);
//        $file_cropped = $folderPath_cropped . uniqid() . '.png';
                $filename_cropped = 'work_' . $this->work['id'] . '_cropped.' . $image_type_cropped;
                $file_cropped = $folderPath_cropped . $filename_cropped;
                file_put_contents($file_cropped, $image_base64);

                $cur_width = Image::load($file_cropped)->getWidth();
                if ($cur_width > 600) {
                    Image::load($file_cropped)
                        ->width(600)
                        ->optimize()
                        ->save($file_cropped);
                }
                $picture_cropped = '/img/work_pics/work_' . $this->work['id'] . '_cropped.' . $image_type_cropped;
            }

            // ------------------------------------------------------------------------------

            if ($this->file ?? 0 != null) {
                Work::where('id', $this->work['id'])->update([
                    'picture' => $picture,
                    'picture_cropped' => $picture_cropped
                ]);
            }

            session()->flash('show_modal', 'yes');
            session()->flash('alert_type', 'success');
            session()->flash('alert_title', 'Отлично!');

            if ($this->form_type == 'create') {
                session()->flash('alert_text', 'Произведение успешно добавлено!');
            } else {
                session()->flash('alert_text', 'Произведение успешно сохранено!');
            }


            return redirect($this->back_after_work_adding['url']);

        }
    }


}
