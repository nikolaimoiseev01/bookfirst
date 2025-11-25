<?php

namespace App\Livewire\Pages\Auth;

use App\Livewire\Forms\LoginForm;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Validate;
use Livewire\Component;

class LoginPage extends Component
{
    public function render()
    {
        return view('livewire.pages.auth.login-page');
    }

    #[Validate('required|string|email')]
    public string $email = '';

    #[Validate('required|string')]
    public string $password = '';

    #[Validate('boolean')]
    public bool $remember = false;

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        if (!Auth::attempt($this->only(['email', 'password']), $this->remember)) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'form.email' => trans('auth.failed'),
            ]);
        }

        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Ensure the authentication request is not rate limited.
     */
    protected function ensureIsNotRateLimited(): void
    {
        if (!RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout(request()));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'form.email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the authentication rate limiting throttle key.
     */
    protected function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->email) . '|' . request()->ip());
    }

    public function messages(): array
    {
        return [
            'email.required' => 'Email обязателен для заполнения',
            'password.required' => 'Пароль обязателен для заполнения',
            'email' => 'Введен невалидный Email. Нужен вида test@mail.ru',
        ];
    }

     /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        try {
            $this->validate();
            $this->authenticate();
            Session::regenerate();

            $this->redirectIntended(
                default: route('account.participations', absolute: false),
                navigate: false
            );

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
