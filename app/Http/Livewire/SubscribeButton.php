<?php

namespace App\Http\Livewire;

use App\Models\user_subscription;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class SubscribeButton extends Component
{
    Public $user_to_subscribe;
    Public $subscription_check;

    public function render()
    {
        $this->subscription_check = user_subscription::
            where('user_id', Auth::user()->id)
            ->where('subscribed_to_user_id', $this->user_to_subscribe)
                ->value('id') > 0;
//       dd($this->subscription_check);
        return view('livewire.subscribe-button', [
            'subscription_check' => $this->subscription_check
        ]);


    }

    public function subscribe()
    {

        $new_user_subsriber = new user_subscription();
        $new_user_subsriber->user_id = Auth::user()->id;
        $new_user_subsriber->subscribed_to_user_id = $this->user_to_subscribe;
        $new_user_subsriber->save();


        $this->dispatchBrowserEvent('subscribe');

//        $this->dispatchBrowserEvent('swal:modal', [
//            'type' => 'success',
//            'title' => 'Отлично!',
//            'text' => 'Вы успешно подписались на пользователя!',
//        ]);
    }

    public function unsubscribe()
    {

        user_subscription::
            where('user_id',Auth::user()->id)
            ->where('subscribed_to_user_id', $this->user_to_subscribe)
             ->delete();


        $this->dispatchBrowserEvent('unsubscribe');


//        $this->dispatchBrowserEvent('swal:modal', [
//            'type' => 'success',
//            'title' => 'Отлично!',
//            'text' => 'Вы успешно подписались на пользователя!',
//        ]);
    }
}
