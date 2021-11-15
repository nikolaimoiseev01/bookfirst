<?php

namespace App\Console\Commands;

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

        $own_book_insides = own_book::where('own_book_status_id', 3)->where('own_book_inside_status_id', 1)->get();
        $own_book_covers = own_book::where('own_book_status_id', 3)->where('own_book_cover_status_id', 1)->get();

        // Создаем дедлайны обложек
        foreach ($own_book_covers as $key=>$own_book_cover) {
            $this_deadline = Date::parse($own_book_cover['cover_deadline'])->format('j F');

            if (Date::parse($own_book_cover['cover_deadline'])->diff(Date::now())->days < 7) {
                $danger_deadline = " *(ДНЕЙ: " . (Date::parse($own_book_cover['cover_deadline'])->diff(Date::now())->days) . ")*";
            }
            else {$danger_deadline = "";};

            $own_book_cover_dates = $own_book_cover_dates . ($key + 1) . '. ' . $own_book_cover['title'] . ": " .
                $this_deadline . ' \n';
        }


        // Создаем дедлайны макетов
        foreach ($own_book_insides as $key=>$own_book_inside) {
            $this_deadline = Date::parse($own_book_inside['inside_deadline'])->format('j F');
            if (Date::parse($own_book_inside['inside_deadline'])->diff(Date::now())->days < 7) {
                $danger_deadline = " *(ДНИ: " . (Date::parse($own_book_inside['inside_deadline'])->diff(Date::now())->days) . ")*";
            }
            else {$danger_deadline = "";};

            $own_book_insides_dates = $own_book_insides_dates . ($key + 1) . '. ' . $own_book_inside['title'] . ": " .
                $this_deadline . $danger_deadline . ' \n';
        }
        //---------------------------------------------

        // Создаем дедлайны сборников
        foreach ($own_book_insides as $key=>$own_book_inside) {
            $this_deadline = Date::parse($own_book_inside['inside_deadline'])->format('j F');
            if (Date::parse($own_book_inside['inside_deadline'])->diff(Date::now())->days < 7) {
                $danger_deadline = " *(ДНИ: " . (Date::parse($own_book_inside['inside_deadline'])->diff(Date::now())->days) . ")*";
            }
            else {$danger_deadline = "";};

            $own_book_insides_dates = $own_book_insides_dates . ($key + 1) . '. ' . $own_book_inside['title'] . ": " .
                $this_deadline . $danger_deadline . ' \n';
        }
        //---------------------------------------------



        $url_back = route('homeAdmin');
        $url_back = "vk.com";

        echo route('homeAdmin');
        // Посылаем Telegram уведомление нам
        Notification::route('telegram', '-506622812')
            ->notify(new TelegramNotification('❗Дедлайны на сегодня❗',
                "*Обложки*" . "\n" . implode("\n", explode('\n', substr($own_book_insides_dates, 0,-2)))  .
                "\n\n" . "*Макеты* " . "\n" . implode("\n", explode('\n', substr($own_book_insides_dates, 0,-2))) ,
                "Админка",
                $url_back));
    }
}
