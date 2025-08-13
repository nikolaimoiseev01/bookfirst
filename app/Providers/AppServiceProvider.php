<?php

namespace App\Providers;

use App\Models\User\User;
use App\Models\Collection\Collection;
use App\Models\Collection\Participation;
use App\Models\OwnBook\OwnBook;
use App\Models\Chat\Message;
use App\Models\Award\AwardType;
use Illuminate\Auth\Middleware\RedirectIfAuthenticated;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Carbon;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }


    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Model::unguard();

        Relation::morphMap([
            'User' => User::class,
            'Collection' => Collection::class,
            'Participation' => Participation::class,
            'OwnBook' => OwnBook::class,
            'Message' => Message::class,
            'AwardType' => AwardType::class,
        ]);

        Carbon::setLocale(config('app.locale')); // Установим локаль из конфигурации

        RedirectIfAuthenticated::redirectUsing(function () {
            return route('account.settings');
        });
    }
}
