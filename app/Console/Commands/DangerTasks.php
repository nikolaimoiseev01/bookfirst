<?php

namespace App\Console\Commands;

use App\Models\Collection;
use App\Models\New_covers_readiness;
use App\Models\own_book;
use App\Notifications\TelegramNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;
use Jenssegers\Date\Date;

class DangerTasks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'DangerTasks';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $debug_mode = False;
        $message_arrays = [];
        $priskazki = [
            'Вы поглятидите, что делается!',
            'Галя, отмена!',
            'Нет времени на раскачку!',
            'Все на баррикады!',
            'Не спать!',
            'Хотели как лучше, а получилось как всегда.',
            'Карету мне, карету!',
            'Здорова, бандиты.',
            'Совпадение? Не думаю...',
            'Почем опиум для народа?!',
            'Show must go on!',
            'Спокойно! Сядем все!',
            'Шеф, всё пропало!',
            'Хьюстон, у нас проблемы!',
            'Дежурный по роте, на выход!',
            'Ну что, доигрались?!',
            'Джингл беллз!',
            'Внимание всем постам!',
            'Неладно что-то в Датском королевстве...',
            'Свистать всех наверх!',
        ];
        $priskazki_kris = [
            'Кристинка, есть работка!',
            'Кристиночка, поищи время поработать!',
            'Без тебя не справлюсь, Крис!',
            'Кристинка, что делать? '
        ];

        //region -- Идем по каждому сборнику, чтобы напомнить про дедлайны
        $collections = Collection::where('col_status_id', '<>', 9)->get();

        foreach ($collections as $collection) {

            $text = null;

            $random_priskazka = $priskazki[array_rand($priskazki)];
            $title_short = str_replace(array('Современный', 'Поэзии', 'Сокровенные', '.', ' '), "", $collection->title);
            $title_short = str_replace(array('Выпуск'), " ", $title_short);

            if ($collection['col_status_id'] == 1) {
                $col_deadline = Date::parse($collection->col_date2)->format('j F');
                $deadline_days = Date::parse($col_deadline)->diff(Date::now());
                // Если разница положительна (deadline в будущем), инвертируем значение
                $deadline_days = $deadline_days->days * ($deadline_days->invert === 0 ? -1 : 1);

                if ($deadline_days < 3 && $deadline_days >= 0)
                    $text = "*{$title_short}* нужно сверстать до *{$col_deadline}*. Осталось дней: {$deadline_days}";
                elseif ($deadline_days < 0) {
                    $text = "*ПРОСРОЧКА!* *{$title_short}* нужно было сверстать *{$col_deadline}*. Дней просрочки: " . $deadline_days * -1;
                }

            } elseif ($collection['col_status_id'] == 2) {
                $col_deadline = Date::parse($collection->col_date3)->format('j F');
                $deadline_days = Date::parse($col_deadline)->diff(Date::now());
                // Если разница положительна (deadline в будущем), инвертируем значение
                $deadline_days = $deadline_days->days * ($deadline_days->invert === 0 ? -1 : 1);

                if ($deadline_days < 3 && $deadline_days >= 0)
                    $text = "*{$title_short}* нужно отправлять в печать до *{$col_deadline}*. Осталось дней: {$deadline_days}";
                elseif ($deadline_days < 0) {
                    $text = "*ПРОСРОЧКА!* *{$title_short}* нужно было отправить в печать до *{$col_deadline}*. Дней просрочки: " . $deadline_days * -1;
                }

            } elseif ($collection['col_status_id'] == 3) {
                $col_deadline = Date::parse($collection->col_date4)->format('j F');
                $deadline_days = Date::parse($col_deadline)->diff(Date::now());
                // Если разница положительна (deadline в будущем), инвертируем значение
                $deadline_days = $deadline_days->days * ($deadline_days->invert === 0 ? -1 : 1);

                if ($deadline_days < 3 && $deadline_days >= 0)
                    $text = "Позвонить Светлане! *{$title_short}* должен быть напечатан до *{$col_deadline}*. Осталось дней: {$deadline_days}";
                elseif ($deadline_days < 0) {
                    $text = "*ПРОСРОЧКА!* *{$title_short}* должен был быть напечатан до *{$col_deadline}*. Дней просрочки: " . $deadline_days * -1;
                }

            }

            if ($text ?? null) {
                array_push($message_arrays, [
                    'title' => "🔥 *{$random_priskazka}*",
                    'text' => $text
                ]);
            }


        }
        //endregion


        //region -- Оповещение Крис, что нет новых обложек

        $random_priskazka_kris = $priskazki_kris[array_rand($priskazki_kris)];

        $eol_collections = Collection::where('col_status_id', '<', 3)->first();

        if ($eol_collections['col_status_id'] == 1) {
            $col_deadline = Date::parse($eol_collections->col_date2)->format('j F');
        } else {
            $col_deadline = Date::parse($eol_collections->col_date3)->format('j F');
        }

        $deadline_days = Date::parse($col_deadline)->diff(Date::now());
        // Если разница положительна (deadline в будущем), инвертируем значение
        $deadline_days = $deadline_days->days * ($deadline_days->invert === 0 ? -1 : 1);
        $new_covers_ready = New_covers_readiness::first();

        if ($new_covers_ready['flg_ready'] == 'Ждем новых обложек') {
            if ($deadline_days >= 0)
                $text_kris = "На запуск следующих сборников нет новых обложек :(";
            elseif ($deadline_days < 0) {
                $text_kris = "Сборники уже закончились, а новых обложек все нет :(";
            }
        }

        if ($text_kris ?? null) {
            array_push($message_arrays, [
                'title' => "🔥 *{$random_priskazka_kris}*",
                'text' => $text_kris
            ]);
        }
        //endregion


        //region -- Напоминаем о дедлайнах собственных книг
        $own_book_insides = own_book::where('own_book_status_id', 3)->where('own_book_inside_status_id', 1)->orwhere('own_book_inside_status_id', 3)->get();
        $own_book_covers = own_book::where('own_book_status_id', 3)->where('own_book_cover_status_id', 1)->orwhere('own_book_cover_status_id', 3)->get();
        $own_book_need_prints = own_book::where('own_book_status_id', 5)->get();

        foreach ($own_book_covers as $key => $own_book) {
            $deadline_days = Date::parse($own_book['cover_deadline'])->diff(Date::now());
            // Если разница положительна (deadline в будущем), инвертируем значение
            $deadline_days = $deadline_days->days * ($deadline_days->invert === 0 ? -1 : 1);

            $random_priskazka_kris = $priskazki_kris[array_rand($priskazki_kris)];

            if ($deadline_days < 3 && $deadline_days >= 0)
                $text_own_book_covers = "У автора *" . $own_book['author'] . "* нужно делать обложку! " . "Срок до {$own_book['cover_deadline']}. Осталось дней: {$deadline_days}";
            elseif ($deadline_days < 0) {
                $text_own_book_covers = "*ПРОСРОЧКА!* У автора *" . $own_book['author'] . "* нужно было делать обложку! " . "Дней просрочки: " . $deadline_days * -1;
            }

            if ($text_kris ?? null) {
                array_push($message_arrays, [
                    'title' => "🔥 *{$random_priskazka_kris}*",
                    'text' => $text_own_book_covers
                ]);
            }
        }

        foreach ($own_book_insides as $key => $own_book) {
            $deadline_days = Date::parse($own_book['cover_deadline'])->diff(Date::now());
            // Если разница положительна (deadline в будущем), инвертируем значение
            $deadline_days = $deadline_days->days * ($deadline_days->invert === 0 ? -1 : 1);

            $random_priskazka = $priskazki[array_rand($priskazki)];

            if ($deadline_days < 3 && $deadline_days >= 0)
                $text_own_book_insides = "У автора *" . $own_book['author'] . "* нужно делать макет! " . "Срок до {$own_book['cover_deadline']}. Осталось дней: {$deadline_days}";
            elseif ($deadline_days < 0) {
                $text_own_book_insides = "*ПРОСРОЧКА!* У автора *" . $own_book['author'] . "* нужно было делать макет! " . "Дней просрочки: " . $deadline_days * -1;
            }

            if ($text_kris ?? null) {
                array_push($message_arrays, [
                    'title' => "🔥 *{$random_priskazka}*",
                    'text' => $text_own_book_insides
                ]);
            }
        }

        foreach ($own_book_need_prints as $own_book) {
            $random_priskazka = $priskazki[array_rand($priskazki)];
            $deadline_days = Date::parse($own_book['paid_at_print_only'])->diff(Date::now())->days;

            $text_own_book_need_prints = "Нужно отправить в печать автора *{$own_book['author']}*! Ждет уже дней: {$deadline_days}";

            if ($text_kris ?? null) {
                array_push($message_arrays, [
                    'title' => "🔥 *{$random_priskazka}*",
                    'text' => $text_own_book_need_prints
                ]);
            }
        }


        //endregion


        //region -- Посылаем Telegram уведомление нам

        if ($message_arrays) {
            if ($debug_mode) {
                dd($message_arrays);
            } else {
                foreach ($message_arrays as $message) {
                    Notification::route('telegram', '-506622812')
                        ->notify(new TelegramNotification($message['title'], $message['text'], "Админка", "vk1.com"));
                    sleep(100);
                }
            }
        }


        //endregion

    }
}
