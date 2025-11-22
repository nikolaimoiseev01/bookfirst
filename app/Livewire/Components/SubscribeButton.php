<?php

namespace App\Livewire\Components;

use App\Models\EmailSubscription;
use App\Traits\WithCustomValidation;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class SubscribeButton extends Component
{
    use WithCustomValidation;

    public $email;
    public $subscribed = false;

    public function rules() {
        return [
            'email' => 'required|email|unique:email_subscriptions,email',
        ];
    }

    public function messages() {
        return [
            'email.required' => 'Поле Email обязательно для заполнения.',
            'email.email' => 'Поле Email должно быть валидным email.',
            'email.unique' => 'Такой Email уже есть в системе',
        ];
    }
    public function render()
    {
        if(Auth::check()) {
            $this->subscribed = EmailSubscription::where('user_id', Auth::user()->id)->exists();
        }
        return view('livewire.components.subscribe-button');
    }

    public function subscribe() {
        if($this->customValidate()) {
            EmailSubscription::create([
                'email' => $this->email,
                'user_id' => auth()->id() ?? null,
            ]);
            $this->subscribed = true;
            $this->dispatch('swal',
                title: 'Успешно',
                text: 'Вы успешно подписались на рассылку',
                type: 'success',
            );
        }
    }
}
