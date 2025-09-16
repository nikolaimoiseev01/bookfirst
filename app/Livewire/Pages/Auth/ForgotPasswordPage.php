<?php

namespace App\Livewire\Pages\Auth;

use Illuminate\Validation\ValidationException;
use Livewire\Component;

use Illuminate\Support\Facades\Password;

class ForgotPasswordPage extends Component
{
    public function render()
    {
        return view('livewire.pages.auth.forgot-password-page');
    }

    public string $email = '';

    /**
     * Send a password reset link to the provided email address.
     */
    public function sendPasswordResetLink(): void
    {
        try {

            $this->validate([
                'email' => ['required', 'string', 'email'],
            ]);


            // We will send the password reset link to this user. Once we have attempted
            // to send the link, we will examine the response then see the message we
            // need to show to the user. Finally, we'll send out a proper response.
            $status = Password::sendResetLink(
                $this->only('email')
            );


            if ($status != Password::RESET_LINK_SENT) {
                throw ValidationException::withMessages([
                    'email' => [__($status)],
                ]);
            }

            $this->reset('email');

            session()->flash('status', __($status));

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
