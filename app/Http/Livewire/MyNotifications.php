<?php

namespace App\Http\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class MyNotifications extends Component
{
    public function render()
    {
        return view('livewire.my-notifications');
    }

    public function MarkAsRead($notification_id,$link)
    {

        Auth::user()->unreadNotifications->where('id',$notification_id)->markAsRead();
        return redirect()->to($link);
    }

    public function MarkAllAsRead()
    {
        Auth::user()->unreadNotifications()->update(['read_at' => now()]);
        session()->flash('success', 'success');
        session()->flash('alert_title', 'Отлично!');
        session()->flash('alert_text', 'Все оповещения прочитаны!');
        return redirect()->to(url()->previous());
    }

    public function test()
    {
        dd('test');
    }
}
