<?php

namespace App\Http\Livewire;

use App\Models\Printorder;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class MySettings extends Component
{
    Public $name;
    Public $surname;
    Public $nickname;
    Public $email;
    Public $password;
    Public $show_input = 0;

    protected $listeners = ['MySettings' => '$refresh'];

    public function render()
    {
        return view('livewire.my-settings',[
            'name' => $this->name,
            'surname' => $this->surname,
            'nickname' => $this->nickname,
            'email' => $this->email,
            'password' => $this->password,
        ]);
    }

    public function mount()
    {
        $this->name = Auth::user()->name;
        $this->surname = Auth::user()->surname;
        $this->nickname = Auth::user()->nickname;
        $this->email = Auth::user()->email;
        $this->password = Auth::user()->password;
    }

    public function  show_1() {
        $this->show_input = 1;
    }

    public function  show_0() {
        $this->show_input = 0;
    }


    public function save()
    {
        if(
            $this->name === ''
            OR $this->surname === ''
            OR $this->nickname === ''
            OR $this->email === ''
            OR $this->password === ''
        )
        {
            $this->dispatchBrowserEvent('swal:modal', [
            'type' => 'error',
            'title' => 'Что-то пошло не так',
            'text' => 'Ни одно поле не должно быть пустым!',
        ]);}
        else
        {
            // ---- Редактируем Заказ печатных! ---- //
            User::where('id', Auth::user()->id)->update([
                'name' => $this->name,
                'surname' => $this->surname,
                'nickname' => $this->nickname,
                'email' => $this->email,
            ]);
            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'success',
                'title' => 'Информация успешно обновлена!',
                'text' => '']);

// ----------------------------------------------------------- //
            $this->show_input = 0;
        }
    }
}
