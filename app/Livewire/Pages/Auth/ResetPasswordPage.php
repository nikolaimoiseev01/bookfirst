<?php

namespace App\Livewire\Pages\Auth;

use Illuminate\Validation\ValidationException;
use Livewire\Component;

use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;

class ResetPasswordPage extends Component
{
    public function render()
    {
        return view('livewire.pages.auth.reset-password-page');
    }

    public string $token = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    /**
     * Mount the component.
     */
    public function mount(string $token): void
    {
        $this->token = $token;

        $this->email = request()->string('email');
    }


    public function messages(): array
    {
        return [
            'email.required' => 'Email обязателен для заполнения',
            'password.required' => 'Пароль обязателен для заполнения',
            'email' => 'Введен невалидный Email. Нужен вида test@mail.ru',
            'confirmed' => 'Пароли не совпадают',
            'min' => [
                'string' => 'Пароль содержать быть минимум 8 символов',
            ],
        ];
    }


    /**
     * Reset the password for the given user.
     */
    public function resetPassword(): void
    {
        try {
            $this->validate([
                'token' => ['required'],
                'email' => ['required', 'string', 'email'],
                'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
            ]);

            // Here we will attempt to reset the user's password. If it is successful we
            // will update the password on an actual user model and persist it to the
            // database. Otherwise we will parse the error and return the response.
            $status = Password::reset(
                $this->only('email', 'password', 'password_confirmation', 'token'),
                function ($user) {
                    $user->forceFill([
                        'password' => Hash::make($this->password),
                        'remember_token' => Str::random(60),
                    ])->save();

                    event(new PasswordReset($user));
                }
            );

            // If the password was successfully reset, we will redirect the user back to
            // the application's home authenticated view. If there is an error we can
            // redirect them back to where they came from with their error message.
            if ($status != Password::PASSWORD_RESET) {
                throw ValidationException::withMessages([
                    'email' => [__($status)],
                ]);
            }

            session()->flash('swal', [
                'title' => 'Успешно!',
                'icon' => 'success',
                'text' => 'Пароль изменен! Теперь вы можете войти в систему с новыми данными'
            ]);

            $this->redirect(route('login'), navigate: true);
        } catch (ValidationException $e) {
            // Собираем все ошибки в одну строку или массив
            $messages = collect($e->validator->errors()->all())->implode("<br>");
            // Диспатчим в JS
            $this->dispatch('swal',
                title: 'Ошибка',
                text: $messages,
            );

            throw $e; // чтобы стандартный Livewire тоже знал про ошибки
        }
    }
}
