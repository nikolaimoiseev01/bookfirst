<?php

namespace App\Providers;

use App\Models\ExtPromotion\ExtPromotion;
use App\Models\User\User;
use App\Models\Collection\Collection;
use App\Models\Collection\Participation;
use App\Models\OwnBook\OwnBook;
use App\Models\Chat\Message;
use App\Models\Award\AwardType;
use Filament\Auth\Pages\PasswordReset\RequestPasswordReset;
use Filament\Facades\Filament;
use Filament\Support\Assets\Js;
use Filament\Support\Facades\FilamentAsset;
use Illuminate\Auth\Middleware\RedirectIfAuthenticated;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
    }


    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Model::unguard();

        FilamentAsset::register([
            Js::make('custom-script', 'https://code.jquery.com/jquery-3.7.1.min.js'),
        ]);

        Relation::morphMap([
            'User' => User::class,
            'Collection' => Collection::class,
            'Participation' => Participation::class,
            'OwnBook' => OwnBook::class,
            'Message' => Message::class,
            'AwardType' => AwardType::class,
            'ExtPromotion' => ExtPromotion::class,
        ]);

        Carbon::setLocale(config('app.locale')); // Установим локаль из конфигурации

        RedirectIfAuthenticated::redirectUsing(function () {
            return route('account.settings');
        });



        VerifyEmail::toMailUsing(function (object $notifiable, string $url) {
            return (new MailMessage)
                ->greeting('Здравствуйте, ' . $notifiable->name)
                ->subject('Подтверждение Email')
                ->line('Мы рады приветствовать вас на портале независимого издательства "Первая Книга"! Здесь вы сможете публиковать свои произведения, участвовать в сборниках современных поэтов, а также издавать свои собственные книги! Для того, чтобы подтвердить свою электронную почту, пожалуйста, нажмите на кнопку ниже:')
                ->action('Подтвердить Email', $url)
                ->line(Lang::get('Если вы не регистрировались на портале "Первая Книга", пожалуйста, просто проигнорируйте это письмо.'))
                ->salutation('С уважением. Первая Книга');
        });
        ResetPassword::toMailUsing(function ($notifiable, string $token) {
            $url = url(route('password.reset', [
                'token' => $token,
                'email' => $notifiable->getEmailForPasswordReset(),
            ], false));

            return (new MailMessage)
                ->subject('Сброс пароля')
                ->greeting('Здравствуйте, ' . $notifiable->name . '!')
                ->line('Вы получили это письмо, потому что мы получили запрос на сброс пароля для вашей учетной записи.')
                ->action('Сбросить пароль', $url)
                ->line('Если вы не запрашивали сброс пароля, никаких дополнительных действий не требуется.')
                ->salutation('С уважением. Первая Книга');
        });
    }
}
