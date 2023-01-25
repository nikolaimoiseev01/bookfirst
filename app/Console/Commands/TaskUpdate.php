<?php

namespace App\Console\Commands;

use App\Models\Collection;
use App\Models\own_book;
use App\Notifications\TelegramNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Notification;
use Jenssegers\Date\Date;

class TaskUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'TaskUpdate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Обновление задач';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        App::setLocale('ru');
        $own_book_insides_dates = '';
        $own_book_cover_dates = '';
        $collection_dates = '';
        $col_deadline = '';

        $own_book_insides = own_book::where('own_book_status_id', 3)->where('own_book_inside_status_id', 1)->orwhere('own_book_inside_status_id', 3)->get();
        $own_book_covers = own_book::where('own_book_status_id', 3)->where('own_book_cover_status_id', 1)->orwhere('own_book_cover_status_id', 3)->get();

        $collections = Collection::where('col_status_id', '<', 9)->get();


        foreach ($collections as $key => $collection) {
            if ($collection->col_date1 > date('Y-m-d')) {
                $col_deadline = Date::parse($collection->col_date1)->format('j F');
            }
            elseif ($collection->col_date2 > date('Y-m-d')) {
                $col_deadline = Date::parse($collection->col_date2)->format('j F');
            }
            elseif ($collection->col_date3 > date('Y-m-d')) {
                $col_deadline = Date::parse($collection->col_date3)->format('j F');
            }
            elseif ($collection->col_date4 > date('Y-m-d')) {
                $col_deadline = Date::parse($collection->col_date4)->format('j F');
            }

            if (Date::parse($col_deadline)->diff(Date::now())->days < 3) {
                $danger_deadline = " *(Дней: " . (Date::parse($col_deadline)->diff(Date::now())->days) . ")*";
            } else {
                $danger_deadline = "";
            };



            $collection_dates = $collection_dates . ($key + 1) . '. ' . $collection['title'] . ": " . $collection->col_status['col_status'] . " до " .
                "*" . $col_deadline . "*" . $danger_deadline . ' \n\n';
        }

        // Создаем дедлайны обложек
        foreach ($own_book_covers as $key => $own_book_cover) {
            $this_deadline = Date::parse($own_book_cover['cover_deadline'])->format('j F');

            if (Date::parse($own_book_cover['cover_deadline'])->diff(Date::now())->days < 3) {
                $danger_deadline = " *(Дней: " . (Date::parse($own_book_cover['cover_deadline'])->diff(Date::now())->days) . " 🔥)*";
            } else {
                $danger_deadline = "";
            };

            $own_book_cover_dates = $own_book_cover_dates . ($key + 1) . '. ' . $own_book_cover['title'] . ": " .
                $this_deadline . $danger_deadline . ' \n';
        }


        // Создаем дедлайны макетов
        foreach ($own_book_insides as $key => $own_book_inside) {
            $this_deadline = Date::parse($own_book_inside['inside_deadline'])->format('j F');
            if (Date::parse($own_book_inside['inside_deadline'])->diff(Date::now())->days < 3) {
                $danger_deadline = " *(Дней: " . (Date::parse($own_book_inside['inside_deadline'])->diff(Date::now())->days) . " 🔥)*";
            } else {
                $danger_deadline = "";
            };

            $own_book_insides_dates = $own_book_insides_dates . ($key + 1) . '. ' . $own_book_inside['author'] . ": " .
                $this_deadline . $danger_deadline . ' \n';
        }
        //---------------------------------------------


        $url_back = route('homeAdmin');
        $url_back = "vk.com";


        // Посылаем Telegram уведомление нам
        Notification::route('telegram', '-506622812')
            ->notify(new TelegramNotification('🗓 *НАШИ ДЕДЛАЙНЫ* 🗓',
                "*Обложки*" . "\n" . implode("\n", explode('\n', substr($own_book_cover_dates, 0, -2))) .
                "\n\n" . "*Макеты* " . "\n" . implode("\n", explode('\n', substr($own_book_insides_dates, 0, -2))) .
                "\n\n" . "*Сборники* " . "\n" . implode("\n", explode('\n', substr($collection_dates, 0, -2))),
                "Админка",
                "vk1.com"));
    }
}
