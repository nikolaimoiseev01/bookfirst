<?php

namespace App\Http\Livewire;

use App\Models\Collection;
use App\Models\Participation;
use App\Models\Participation_work;
use App\Models\Printorder;
use App\Models\Work;
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
    public $work_type = "";
    public $work_topic = "";
    public $work_topics = "";
    public $cropped_img = "";

    public function render()
    {

        if ($this->file ?? 0 != null & $this->file_preview == null) {
            if(file_exists(storage_path('app/livewire-tmp/' . $this->file->getfilename()))) {
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
        $work_types = work_type::get();
        $this->work_topics = work_type::where('type', $this->work_type)->get();

//        dd($this->work_topics);

        return view('livewire.create-work', [
            'file_preview' => $this->file_preview,
            'work_types' => $work_types,
            'work_topics' => $this->work_topics
        ]);

    }


    public function storeWork($formData) {


        $folderPath = public_path('img/work_pics/');
        $image_parts = explode(";base64,", $this->cropped_img);
        $image_type_aux = explode("image/", $image_parts[0]);
        $image_type = $image_type_aux[1];
        $image_base64 = base64_decode($image_parts[1]);
        // $file = $folderPath . uniqid() . '.png';
        $filename = time() . '.'. $image_type;
        $file =$folderPath.$filename;
        file_put_contents($file, $image_base64);

        $validator = Validator::make($formData, [
            'work_text' => 'required',
            'work_title' => 'required',
//            'work_type' => 'required',
//            'work_topic' => 'required',
        ]);


        if ($validator->fails()) {
            $errors = $validator->errors();
            foreach ($errors->all() as $message) {
                $error[] = [$message, '<br>'];
            }

            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'error',
                'title' => 'Что-то пошло не так!',
                'text' => implode("<br>",$errors->all()),
            ]);
        }

        $validator->validate();



        $work_last_id = DB::select("SELECT ID FROM WORKS ORDER BY CREATED_AT DESC LIMIT 1");
        $work_next_id = $work_last_id[0]->ID + 1;

        $new_work = new Work();
        $new_work->title = $this->work_title;
        $new_work->text = ($this->work_text);
        $new_work->symbols = $this->symbols;
        $new_work->rows = $this->rows;
        $new_work->pages = $this->pages;
        $new_work->upload_type = 'вручную';
        $new_work->user_id = Auth::user()->id;

//        $work_type_db = work_type::where('type', $this->work_type)->where('topic', $this->work_topic)->value('id');
//
//        $new_work->work_type_id = $work_type_db;
//
//        // Если есть изображение: оптимизируем его и уменьшаем
//        if ($this->file ?? 0 != null) {
//            $file_old_temp_path = public_path('filepond_temp/work_pics/' . $this->file_name);
//            $cur_width = Image::load($file_old_temp_path)->getWidth();
//            if ($cur_width > 350) {
//                Image::load($file_old_temp_path)
//                    ->width(350)
//                    ->optimize()
//                    ->save($file_old_temp_path);
//            }
//            $file_new_path = public_path('img/work_pics/work_' .$work_next_id . '.' . $this->file_extension);
//            File::move($file_old_temp_path, $file_new_path); // перемещаем в нашу временную папку
//            $file_preview = '/img/work_pics/work_' . $work_next_id . '.' . $this->file_extension;
//        } else {
//            $file_preview = null;
//        }
//        // ------------------------------------------------------------------------------
//
//        $new_work->picture = $file_preview;

        $new_work->save();


        session()->flash('show_modal', 'yes');
        session()->flash('alert_type', 'success ');
        session()->flash('alert_title', 'Отлично!');
        session()->flash('alert_text', 'Произведение успешно добавлено!');
        return redirect(Session('back_after_add'));

    }

    public function test_function() {
        dd('test');
    }



}
