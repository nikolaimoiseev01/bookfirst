<?php

namespace App\Providers;

use App\Models\Chat;
use App\Models\Collection;
use App\Models\Message;
use App\Models\own_book;
use App\Models\Participation;
use App\Models\User;
use App\Service\PaymentService;
use Carbon\Carbon;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
        $this->app->bind(PaymentService::class, function ($app) {
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


        $throttleRate = config('mail.throttleToMessagesPerMin');
        if ($throttleRate) {
            $throttlerPlugin = new \Swift_Plugins_ThrottlerPlugin($throttleRate, \Swift_Plugins_ThrottlerPlugin::MESSAGES_PER_MINUTE);
            Mail::getSwiftMailer()->registerPlugin($throttlerPlugin);
        }

        Paginator::useBootstrap();

        setlocale(LC_ALL, 'ru_RU.utf8');
        Carbon::setLocale(config('app.locale'));
        Date::setlocale(config('app.locale'));


        view()->composer('*', function ($view) {

            $check_filament = (str_contains($view->name(), 'Macros')) || (str_contains($view->name(), 'component') || (str_contains($view->name(), 'filament')));

            if (isset($_SERVER['REQUEST_URI'])) {
                $urlParts = explode('/', $_SERVER['REQUEST_URI']);
                $subdomain = $urlParts[1];
            } else {
                $subdomain = 'Default';
            }


            if ($subdomain !== 'admin') { // В новой админке не хотим все это грузить
                if (Auth::user()) {
                    $custom_notifications = Chat::where('flg_chat_read', 0)
                        ->where(function ($query) {
                            $query->where('user_to', Auth::user()->id)
                                ->orWhere('user_created', Auth::user()->id);
                        })
                        ->where('chat_status_id', '<>', '3')->distinct('chats.id')->count('chats.id');
                } else {
                    $custom_notifications = null;
                }


                $new_chats = Chat::where('chat_status_id', 1)
                    ->where(function ($query) {
                        $query->where('user_created', '=', 2)
                            ->orWhere('user_to', '=', 2);
                    })
                    ->count();
            }

            if ($subdomain === "admin_panel") { // Грузим для старой админки

                $own_books_alert = own_book::where('own_book_status_id', 1)
                    ->orwhere(function ($q) {
                        $q->where('own_book_status_id', 5)
                            ->Where('own_book_inside_status_id', 4)
                            ->Where('own_book_cover_status_id', 4);;
                    })
                    ->orwhere(function ($q) {
                        $q->where('own_book_status_id', 3)
                            ->Where('own_book_inside_status_id', 1)
                            ->orWhere('own_book_inside_status_id', 3);;
                    })
                    ->orwhere(function ($q) {
                        $q->where('own_book_status_id', 3)
                            ->Where('own_book_cover_status_id', 1)
                            ->orWhere('own_book_cover_status_id', 3);;
                    })
                    ->count();

                $new_participants = Participation::where('pat_status_id', 1)->count();
            };

            $user_id_logged_in = Auth::user()->id ?? 0;


            $view->with([
                'custom_notifications' => $custom_notifications ?? null,
                'new_participants' => $new_participants ?? null,
                'new_chats' => $new_chats ?? null,
                'own_books_alert' => $own_books_alert ?? null,
                'subdomain' => $subdomain ?? null,
                'user_id_logged_in' => $user_id_logged_in
            ]);

        });


    }
}
