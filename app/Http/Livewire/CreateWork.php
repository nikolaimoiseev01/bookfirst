<?php

namespace App\Http\Livewire;

use App\Models\Collection;
use App\Models\Participation;
use App\Models\Participation_work;
use App\Models\Printorder;
use App\Models\Work;
use App\Models\work_topic;
use App\Models\work_type;
use App\Notifications\new_participation;
use App\Rules\SameParticipation;
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

class CreateWork extends Component
{
    use WithFileUploads;

    public $work_title;
    public $work_text;
    public $symbols;
    public $rows;
    public $pages;
    public $file;
    public $file_preview;
    public $file_name;
    public $file_extension;
    public $work_type;
    public $work_topic;
    public $work_topics = "";
    public $cropped_img = "";

    public function render()
    {

        if ($this->file ?? 0 != null & $this->file_preview == null) {
            if (file_exists(storage_path('app/livewire-tmp/' . $this->file->getfilename()))) {
                $file_old_temp_path = storage_path('app/livewire-tmp/' . $this->file->getfilename());
                $file_new_temp_path = public_path('filepond_temp/work_pics/' . $this->file->getfilename());
                $this->file_name = $this->file->getfilename();
                $this->file_extension = $this->file->extension();
                File::move($file_old_temp_path, $file_new_temp_path); // перемещаем в нашу временную папку
                $this->file_preview = '/filepond_temp/work_pics/' . $this->file->getfilename();
                $this->dispatchBrowserEvent('update_preview');
            }
        } else {
            $file_preview = "";
        }

        $this->work_types = work_type::orderBy('name', 'asc')->get();
        $this->work_topics = work_topic::orderBy('name', 'asc')->get();


        return view('livewire.create-work', [
            'file_preview' => $this->file_preview,
            'work_topics' => $this->work_topics,
            'work_types' => $this->work_types,
        ]);

    }


    public function storeWork($formData)
    {


        $validator = Validator::make($formData, [
            'work_text' => 'required',
            'work_title' => 'required',
            'work_type' => 'required',
            'work_topic' => 'required',
        ]);

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

        if (!empty($errors_array)) {
            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'error',
                'title' => 'Что-то пошло не так!',
                'text' => implode("<br>", $errors_array),
            ]);
        }

        // --------- //Ищем ошибки в заполнении  --------- //

        if (empty($errors_array)) {


            $new_work = new Work();
            $new_work->title = $this->work_title;
            $new_work->text = ($this->work_text);
            $new_work->symbols = $this->symbols;
            $new_work->rows = $this->rows;
            $new_work->pages = $this->pages;
            $new_work->upload_type = 'вручную';
            $new_work->user_id = Auth::user()->id;
            $new_work->work_type_id = $this->work_type;
            $new_work->work_topic_id = $this->work_topic;

            $new_work->save();


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
                $file_new_path = public_path('img/work_pics/work_' . $new_work->id . '.' . $this->file_extension);
                File::move($file_old_temp_path, $file_new_path); // перемещаем в нашу временную папку
                $picture = '/img/work_pics/work_' . $new_work->id . '.' . $this->file_extension;
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
                $filename_cropped = 'work_' . $new_work->id . '_cropped.' . $image_type_cropped;
                $file_cropped = $folderPath_cropped . $filename_cropped;
                file_put_contents($file_cropped, $image_base64);

                $cur_width = Image::load($file_cropped)->getWidth();
                if ($cur_width > 600) {
                    Image::load($file_cropped)
                        ->width(600)
                        ->optimize()
                        ->save($file_cropped);
                }
                $picture_cropped = '/img/work_pics/work_' . $new_work->id . '_cropped.' . $image_type_cropped;
            }

            // ------------------------------------------------------------------------------

            if ($this->file ?? 0 != null) {
                Work::where('id', $new_work->id)->update([
                    'picture' => $picture,
                    'picture_cropped' => $picture_cropped
                ]);
            }

            session()->flash('show_modal', 'yes');
            session()->flash('alert_type', 'success ');
            session()->flash('alert_title', 'Отлично!');
            session()->flash('alert_text', 'Произведение успешно добавлено!');
            return redirect(Session('back_after_add'));

        }
    }


}
