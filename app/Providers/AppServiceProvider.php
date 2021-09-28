<?php

namespace App\Providers;

use App\Models\Chat;
use App\Models\Collection;
use App\Models\own_book;
use App\Models\Participation;
use App\Service\PaymentService;
use Carbon\Carbon;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Jenssegers\Date\Date;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any participation services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(PaymentService::class, function($app) {
           return new PaymentService();
        });
    }

    /**
     * Bootstrap any participation services.
     *
     * @return void
     */
    public function boot()
    {
        Paginator::useBootstrap();

        setlocale(LC_ALL, 'ru_RU.utf8');
        Carbon::setLocale(config('app.locale'));
        Date::setlocale(config('app.locale'));

        view()->composer('*', function ($view)
        {
            $notifications = Auth::user()->unreadNotifications ?? 0;
            $new_participants = Participation::where('pat_status_id', 1)->count();
            $new_chats = Chat::where('chat_status_id', 1)->count();
            $own_books_alert = own_book::where('own_book_status_id', 1)
                ->orwhere('own_book_status_id', 5)
                ->orwhere('own_book_inside_status_id', 1)
                ->orwhere('own_book_cover_status_id', 1)
                ->count();
            //...with this variable
            $view->with([
                'notifications' => $notifications,
                'new_participants' => $new_participants,
                'new_chats' => $new_chats,
                'own_books_alert' => $own_books_alert,
            ]);
        });


    }
}
