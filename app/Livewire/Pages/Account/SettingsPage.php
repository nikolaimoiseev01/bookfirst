<?php

namespace App\Livewire\Pages\Account;

use App\Models\User\User;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

use App\Livewire\Actions\Logout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;

class SettingsPage extends Component
{
    public $user;
    public $name;
    public $surname;
    public $nickname;

    public function render()
    {
        return view('livewire.pages.account.settings-page')->layout('layouts.account');
    }

    public function mount()
    {
        $this->user = Auth::user();
        $this->name = $this->user['name'];
        $this->surname = $this->user['surname'];
        $this->nickname = $this->user['nickname'];
    }

    public function logout(): void
    {
        Auth::logout();
        Session::invalidate();
        Session::regenerateToken();

        $this->redirect(route('portal.index'), navigate: true);
    }

    public function update() {
        $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'surname' => ['required', 'string', 'max:255'],
            'nickname' => ['required', 'string', 'max:255']
        ]);
        dd('test', $this->validate());
    }


}
