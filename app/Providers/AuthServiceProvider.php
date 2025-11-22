<?php

namespace App\Providers;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Lang;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the participation.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        ResetPassword::toMailUsing(function ($notifiable, $token) {
            return (new MailMessage)
                ->subject(Lang::get('Восстановление пароля'))
                ->greeting('Здравствуйте!')
                ->line(Lang::get('Вы получили это письмо, поскольку мы получили запрос на сброс пароля для вашей учетной записи.'))
                ->action(Lang::get('Сбросить пароль'), route('password.reset', $token))
                ->line(Lang::get('Ссылка перестанет работать через минут: :count.', ['count' => config('auth.passwords.' . config('auth.defaults.passwords') . '.expire')]))
                ->line(Lang::get('Если вы не запрашивали сброс пароля, никаких дальнейших действий не требуется.'))
                ->salutation('С уважением, ' . config('app.name'))
                ;
        });
    }
}
