<?php

namespace App\Livewire\Pages\Auth;

use Illuminate\Validation\Rules\Password;
use App\Models\User\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

class RegisterPage extends Component
{
    public function render()
    {
        return view('livewire.pages.auth.register-page');
    }

    public string $name = '';
    public string $surname = '';
    public string $nickname = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    /**
     * Handle an incoming registration request.
     */

    protected function messages(): array
    {
        return [
            'name.required' => 'Имя автора обязательно для заполнения',
            'surname.required' => 'Фамилия автора обязательно для заполнения',
            'email.required' => 'Email обязателен для заполнения',
            'password.required' => 'Пароль обязателен для заполнения',
            'password_confirmation.required' => 'Повторение пароля обязательно для заполнения',
            'email.unique' => 'Такой email уже есть в системе',
            'password.min' => 'Минимальное количество символов для пароля: :min',
            'password.confirmed' => 'Пароли не совпадают',
        ];
    }
    public function register(): void
    {
        try {
            $validated = $this->validate([
                'name' => ['required', 'string', 'max:255'],
                'surname' => ['required', 'string', 'max:255'],
                'nickname' => ['string', 'max:255'],
                'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
                'password' => ['required', 'string', 'confirmed', Password::defaults()],
            ]);

            $validated['password'] = Hash::make($validated['password']);

            event(new Registered($user = User::create($validated)));

            $user->assignRole('User');
            Auth::login($user);

            $this->redirect(route('account.participations', absolute: false), navigate: true);
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
