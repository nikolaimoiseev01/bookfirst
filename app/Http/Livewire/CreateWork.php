<?php

namespace App\Http\Livewire;

use App\Models\Collection;
use App\Models\Participation;
use App\Models\Participation_work;
use App\Models\Printorder;
use App\Models\Work;
use App\Notifications\new_participation;
use App\Rules\SameParticipation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;
use Livewire\Component;

class CreateWork extends Component
{

    public $work_title;
    public $work_text;
    public $symbols;
    public $rows;
    public $pages;

    public function render()
    {
        return view('livewire.create-work');
        $this->dispatchBrowserEvent('livewire:load');
    }
    public function storeWork($formData) {

        $validator = Validator::make($formData, [
            'work_text' => 'required',
            'work_title' => 'required',
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

        $new_work = new Work();
        $new_work->title = $this->work_title;
        $new_work->text = ($this->work_text);
        $new_work->symbols = $this->symbols;
        $new_work->rows = $this->rows;
        $new_work->pages = $this->pages;
        $new_work->upload_type = 'вручную';
        $new_work->user_id = Auth::user()->id;
        $new_work->save();


        session()->flash('show_modal', 'yes');
        session()->flash('alert_type', 'success ');
        session()->flash('alert_title', 'Отлично!');
        session()->flash('alert_text', 'Произведение успешно добавлено!');
        return redirect(Session('back_after_add'));

    }

}
