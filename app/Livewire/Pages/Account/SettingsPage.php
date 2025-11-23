<?php

namespace App\Livewire\Pages\Account;

use App\Models\User\User;
use App\Traits\WithCustomValidation;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

use App\Livewire\Actions\Logout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
use Spatie\LivewireFilepond\WithFilePond;

class SettingsPage extends Component
{
    use WithFilePond, WithCustomValidation;
    public $user;
    public $name;
    public $surname;
    public $nickname;
    public $showEdit=false;

    public $file;

    public function render()
    {
        return view('livewire.pages.account.settings-page')->layout('layouts.account');
    }

    public function rules()
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'surname' => ['required', 'string', 'max:255'],
            'file' => 'nullable|file|mimetypes:image/jpg,image/jpeg,image/png|max:3000',
        ];
    }

    public function messages()
    {
        return [
            'file.mimetypes' => 'У прикрепленного файла должен быть другой формат: jpg, jpeg, png!',
            'name.required' => 'Имя обязательно для заполнения',
            'surname.required' => 'Фамилия обязательна для заполнения'
        ];
    }

    public function mount()
    {
        $this->user = Auth::user()->load('media');
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
        if ($this->customValidate()) {
            DB::transaction(function() {
                $this->user->update([
                    'name' => $this->name,
                    'surname' => $this->surname,
                    'nickname' => $this->nickname
                ]);
                if ($this->file) {
                    $this->user->clearMediaCollection('avatar');
                    $this->user
                        ->addMedia($this->file->getRealPath())       // путь до tmp файла
                        ->usingFileName($this->file->getClientOriginalName()) // оригинальное имя
                        ->toMediaCollection('avatar');   // твоя коллекция
                }
                $this->showEdit = false;
                $this->dispatch('swal', type: 'success', title: 'Данные успешно обновлены');
                $this->dispatch('$refresh');
            });
        }
    }

}
