<?php

namespace App\Http\Livewire;

use App\Models\preview_comment;
use App\Models\subscriber;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Subscription extends Component
{

    protected $listeners = ['make_subscription'];


    public function render()
    {
        if (Auth::user()->id ?? 0) {


            $sub_pre_check = subscriber::where('email', Auth::user()->email)->value('id') ?? 0;
            if ($sub_pre_check > 0 && $sub_pre_check <> null) {
                $sub_check = subscriber::where('email', Auth::user()->email)->value('id');
            } else {
                $sub_check = 0;
            }
        } else {
            $sub_check = 0;
        }

        return view('livewire.subscription', [
            'sub_check' => $sub_check,
        ]);
    }

    public function make_subscription($email)
    {

        if (subscriber::where('email', $email)->value('id') > 0) {
            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'error',
                'title' => 'Что-то пошло не так!',
                'text' => 'Этот Email уже подписан в нашей базе!',
            ]);
        } else {
            $new_subsriber = new subscriber();
            $new_subsriber->email = $email;
            if (Auth::user()->id ?? 0 > 0) {
                $new_subsriber->user_id = Auth::user()->id;
            }
            $new_subsriber->save();




            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'success',
                'title' => 'Отлично!',
                'text' => 'Мы успешно подписали почту ' . $email . ' на новости нашего издательства!',
            ]);
        }

    }

}
