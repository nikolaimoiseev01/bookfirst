<?php

namespace App\Providers;

use Illuminate\Auth\Middleware\RedirectIfAuthenticated;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
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
            'User' => \App\Models\User\User::class,
            'Collection' => \App\Models\Collection\Collection::class,
            'Participation' => \App\Models\Collection\Participation::class,
            'OwnBook' => \App\Models\OwnBook\OwnBook::class,
            'Message' => \App\Models\Chat\Message::class,
            'AwardType' => \App\Models\Award\AwardType::class,
        ]);

        RedirectIfAuthenticated::redirectUsing(function () {
            return route('account.settings');
        });
    }
}
