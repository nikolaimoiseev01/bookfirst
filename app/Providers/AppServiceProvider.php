<?php

namespace App\Providers;

use App\Models\Chat;
use App\Models\Collection;
use App\Models\Message;
use App\Models\own_book;
use App\Models\Participation;
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
        Paginator::useBootstrap();

        setlocale(LC_ALL, 'ru_RU.utf8');
        Carbon::setLocale(config('app.locale'));
        Date::setlocale(config('app.locale'));


        view()->composer('*', function ($view) {
            if (Auth::user()->id ?? null) {
                $this->query = '
            SELECT
            count(distinct m.chat_id) as noti_cnt
            FROM messages m
            join chats c on m.chat_id = c.id
            JOIN (
                SELECT chat_id,  MAX(m.updated_at) AS max_mes_upd
                FROM messages m
                group by chat_id
            ) b ON m.chat_id = b.chat_id and m.updated_at = b.max_mes_upd
            where (m.flag_mes_read = 0 or m.flag_mes_read is null) and c.chat_status_id <> 3 and m.user_to = ' . Auth::user()->id;
//select * from (
//        SELECT c.*
//
//        ,u_cr.id as u_cr_id, u_cr.avatar as u_cr_avatar ,ifnull(u_cr.nickname, concat(u_cr.name, " ",u_cr.surname)) as u_cr_name
//        ,u_to.id as u_to_id, u_to.avatar as u_to_avatar ,ifnull(u_to.nickname, concat(u_to.name, " ",u_to.surname)) as u_to_name
//        ,(Row_Number() over (partition by c.id order by m.created_at desc)) as rn, m.text as last_mes_text, m.created_at as last_mes_created
//        ,m.id as last_mes_id
//        ,m.user_to as last_mes_to
//        ,m.flag_mes_read
//        FROM chats as c
//        Join users as u_cr on u_cr.id = c.user_created
//        Join users as u_to on u_to.id = c.user_to
//        Left Join messages as m on m.chat_id = c.id
//
//        ) a
//        where a.rn = 1
//        and (a.flag_mes_read is null or a.flag_mes_read = 0)
//        and a.last_mes_to = ' . Auth::user()->id . '
//        order by last_mes_created desc';


                $notifications = DB::select(DB::raw($this->query))[0]->noti_cnt;
            } else {
                $notifications = null;
            }
//            dd($notifications);
            $new_participants = Participation::where('pat_status_id', 1)->count();
            $new_chats = Chat::where('chat_status_id', 1)->count();
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
            //...with this variable

            if(isset($_SERVER['REQUEST_URI']))  {
                $urlParts = explode('/', $_SERVER['REQUEST_URI']);
                $subdomain = $urlParts[1];
            } else {
                $subdomain = 'Default';
            }


//            if (isset($_SERVER['HTTP_HOST']) && !empty($_SERVER['HTTP_HOST'])) {
//                $urlParts = explode('.', $_SERVER['HTTP_HOST']);
//                $subdomain = $urlParts[0];
//            } else {
//                $subdomain = "";
//            }


            $user_id_logged_in = Auth::user()->id ?? 0;

            $view->with([
                'notifications' => $notifications,
                'new_participants' => $new_participants,
                'new_chats' => $new_chats,
                'own_books_alert' => $own_books_alert,
                'subdomain' => $subdomain,
                'user_id_logged_in' => $user_id_logged_in
            ]);
        });


    }
}
