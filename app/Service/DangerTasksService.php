<?php

namespace App\Service;

use App\Models\Collection;
use App\Models\InnerTask;
use App\Models\New_covers_readiness;
use App\Models\own_book;
use App\Notifications\TelegramNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Jenssegers\Date\Date;

class DangerTasksService
{
    public function update($manual_update = false)
    {
        $debug_mode = True;
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
        $innerTaskTitles = [
            '0' => 'Сделать обложку сборнику',
            '1' => 'Сверстать сборник',
            '2' => 'Исправить ошибки в сборнике',
            '3' => 'Выбрать победителей в сборнике',
            '4' => 'Отправить сборник в печать',
            '5' => 'Проверить печать сборника',
            '6' => 'Отправить сборник людям',

            '50' => [
                1 => 'Сверстать ВБ',
                3 => 'Исправить ВБ'
            ],
            '51' => [
                1 => 'Сделать обложку',
                3 => 'Исправить обложку'
            ],
            '54' => 'Отправить книгу на печать',
            '55' => 'Проверить печать книги',
        ];


        DB::Transaction(function () use ($debug_mode, $message_arrays, $priskazki, $priskazki_kris, $innerTaskTitles, $manual_update) {


            $deadline_days_threshold = 5;

            //region -- Идем по каждому сборнику, чтобы напомнить про дедлайны
            $collections = Collection::where('col_status_id', '<>', 9)->get() ?? null;

            if ($collections) {
                foreach ($collections as $collection) {

                    $title = null;
                    $text = null;
                    $innerTask = null;

                    $random_priskazka = $priskazki[array_rand($priskazki)];
                    $title_short = str_replace(array('Современный', 'Поэзии', 'Сокровенные', '.', ' '), "", $collection->title);
                    $title_short = str_replace(array('Выпуск'), " ", $title_short);

                    if ($collection['col_status_id'] == 1) {
                        $col_deadline = Date::parse($collection->col_date2)->format('j F');
                        $deadline_days = Date::parse($col_deadline)->diff(Date::now());
                        // Если разница положительна (deadline в будущем), инвертируем значение
                        $deadline_days = $deadline_days->days * ($deadline_days->invert === 0 ? -1 : 1);

                        $title = "Нужна верстка сборника! Дней: {$deadline_days}";

                        if ($deadline_days < $deadline_days_threshold && $deadline_days >= 0) {
                            $text = "*{$title_short}* нужно сверстать до *{$col_deadline}*. Осталось дней: {$deadline_days}";
                            $innerTask = [
                                'col_id' => $collection->id,
                                'type_id' => 1,
                                'title' => $innerTaskTitles[1],
                                'deadline' => $collection->col_date2
                            ];
                        } elseif ($deadline_days < 0) {
                            $text = "*ПРОСРОЧКА!* *{$title_short}* нужно было сверстать *{$col_deadline}*. Дней просрочки: " . $deadline_days * -1;
                        }

                    } elseif ($collection['col_status_id'] == 2) {
                        $col_deadline = Date::parse($collection->col_date3)->format('j F');
                        $deadline_days = Date::parse($col_deadline)->diff(Date::now());
                        // Если разница положительна (deadline в будущем), инвертируем значение
                        $deadline_days = $deadline_days->days * ($deadline_days->invert === 0 ? -1 : 1);

                        $title = "Нужна отправка сборника! Дней: {$deadline_days}";

                        if ($deadline_days < $deadline_days_threshold && $deadline_days >= 0) {
                            $text = "*{$title_short}* нужно отправлять в печать до *{$col_deadline}*. Осталось дней: {$deadline_days}";
                            $innerTask = [
                                'col_id' => $collection->id,
                                'type_id' => 1,
                                'title' => $innerTaskTitles[4],
                                'deadline' => $collection->col_date3
                            ];

                            /* Доп задача */
                            $winners_deadline = Date::parse($collection->col_date3)->addDays(-3);
                            $winners_add_title = "Нужно выбрать победителей! Дней: {$winners_deadline}";
                            $winners_add_text = "*{$title_short}* нужно выбрать победителей до *{$winners_deadline}*";
                            $winners_add_inner_task = [
                                'col_id' => $collection->id,
                                'type_id' => 1,
                                'title' => $innerTaskTitles[3],
                                'deadline' =>  $winners_deadline
                            ];
                            $message_arrays[] = [
                                'title' => "🔥 *{$winners_add_title}*",
                                'text' => $winners_add_text,
                                'innerTask' => $winners_add_inner_task
                            ];

                        } elseif ($deadline_days < 0) {
                            $text = "*ПРОСРОЧКА!* *{$title_short}* нужно было отправить в печать до *{$col_deadline}*. Дней просрочки: " . $deadline_days * -1;
                        }

                    } elseif ($collection['col_status_id'] == 3) {
                        $col_deadline = Date::parse($collection->col_date4)->format('j F');
                        $deadline_days = Date::parse($col_deadline)->diff(Date::now());
                        // Если разница положительна (deadline в будущем), инвертируем значение
                        $deadline_days = $deadline_days->days * ($deadline_days->invert === 0 ? -1 : 1);

                        $title = "Печать сборника уже готова! Дней: {$deadline_days}";

                        if ($deadline_days < $deadline_days_threshold && $deadline_days >= 0) {
                            $text = "Позвонить Светлане! *{$title_short}* должен быть напечатан до *{$col_deadline}*. Осталось дней: {$deadline_days}";
                        } elseif ($deadline_days < 0) {
                            $text = "*ПРОСРОЧКА!* *{$title_short}* должен был быть напечатан до *{$col_deadline}*. Дней просрочки: " . $deadline_days * -1;
                        }

                    }


                    if ($text ?? null) {
                        $message_arrays[] = [
                            'title' => "🔥 *{$title}*",
                            'text' => $text,
                            'innerTask' => $innerTask
                        ];
                    }


                }
            }

            //endregion


            //region -- Оповещение Крис, что нет новых обложек

            $random_priskazka_kris = $priskazki_kris[array_rand($priskazki_kris)];

            $eol_collections = Collection::where('col_status_id', '<', 3)->first();

            if ($eol_collections) {
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
                    $title = "КРИС, ОБЛОЖКИ!";
                    if ($deadline_days >= 0)
                        $text_kris = "На запуск следующих сборников нет новых обложек :(";
                    elseif ($deadline_days < 0) {
                        $text_kris = "Сборники уже закончились, а новых обложек все нет :(";
                    }
                }

                if ($text_kris ?? null) {
                    $message_arrays[] = [
                        'title' => "🖌 *{$title}*",
                        'text' => $text_kris
                    ];
                }
            }
            //endregion


            //region -- Напоминаем о дедлайнах собственных книг
            $own_book_insides = own_book::where('own_book_status_id', 3)->where('own_book_inside_status_id', 1)->orwhere('own_book_inside_status_id', 3)->get() ?? null;
            $own_book_covers = own_book::where('own_book_status_id', 3)->where('own_book_cover_status_id', 1)->orwhere('own_book_cover_status_id', 3)->get() ?? null;
            $own_book_need_prints = own_book::where('own_book_status_id', 5)->get() ?? null;

            if ($own_book_covers) {
                foreach ($own_book_covers as $key => $own_book) {
                    $text_own_book_covers = null;
                    $innerTask = null;
                    $deadline_days = Date::parse($own_book['cover_deadline'])->diff(Date::now());
                    // Если разница положительна (deadline в будущем), инвертируем значение
                    $deadline_days = $deadline_days->days * ($deadline_days->invert === 0 ? -1 : 1);

                    $random_priskazka_kris = $priskazki_kris[array_rand($priskazki_kris)];

                    $title = "КРИС, ОБЛОЖКИ! Дней: {$deadline_days}";

                    if ($deadline_days < $deadline_days_threshold && $deadline_days >= 0) {
                        $text_own_book_covers = "У автора *" . $own_book['author'] . "* нужно делать обложку! " . "Срок до {$own_book['cover_deadline']}. Осталось дней: {$deadline_days}";
                        $innerTask = [
                            'own_book_id' => $own_book['id'],
                            'type_id' => 2,
                            'title' => $innerTaskTitles[51][$own_book['own_book_cover_status_id']],
                            'original_status' => $own_book->own_book_cover_status['status_title'],
                            'deadline' => $own_book['cover_deadline']
                        ];
                    } elseif ($deadline_days < 0) {
                        $text_own_book_covers = "*ПРОСРОЧКА!* У автора *" . $own_book['author'] . "* нужно было делать обложку! " . "Дней просрочки: " . $deadline_days * -1;
                    }

                    if ($text_own_book_covers ?? null) {
                        $message_arrays[] = [
                            'title' => "🖌 *{$title}*",
                            'text' => $text_own_book_covers,
                            'innerTask' => $innerTask
                        ];
                    }
                }
            }


            if ($own_book_insides) {
                foreach ($own_book_insides as $key => $own_book) {
                    $text_own_book_insides = null;
                    $innerTask = null;
                    $deadline_days = Date::parse($own_book['inside_deadline'])->diff(Date::now());
                    // Если разница положительна (deadline в будущем), инвертируем значение
                    $deadline_days = $deadline_days->days * ($deadline_days->invert === 0 ? -1 : 1);

                    $random_priskazka = $priskazki[array_rand($priskazki)];

                    $title = "СК. Нужно верстать! Дней: {$deadline_days}";

                    if ($deadline_days < $deadline_days_threshold && $deadline_days >= 0) {
                        $text_own_book_insides = "У автора *" . $own_book['author'] . "* нужно делать макет! " . "Срок до {$own_book['cover_deadline']}. Осталось дней: {$deadline_days}";
                        $innerTask = [
                            'own_book_id' => $own_book['id'],
                            'type_id' => 2,
                            'title' => $innerTaskTitles[50][$own_book['own_book_inside_status_id']],
                            'original_status' => $own_book->own_book_inside_status['status_title'],
                            'deadline' => $own_book['inside_deadline']
                        ];

                    } elseif ($deadline_days < 0) {
                        $text_own_book_insides = "*ПРОСРОЧКА!* У автора *" . $own_book['author'] . "* нужно было делать макет! " . "Дней просрочки: " . $deadline_days * -1;
                    }

                    if ($text_own_book_insides ?? null) {
                        $message_arrays[] = [
                            'title' => "🔥 *{$title}*",
                            'text' => $text_own_book_insides,
                            'innerTask' => $innerTask
                        ];
                    }
                }
            }


            if ($own_book_need_prints) {
                foreach ($own_book_need_prints as $own_book) {
                    $innerTask = null;
                    $deadline_days = Date::parse($own_book['paid_at_print_only'])->diff(Date::now())->days;

                    $text_own_book_need_prints = "Нужно отправить в печать автора *{$own_book['author']}*! Ждет уже дней: {$deadline_days}";

                    $title = "СК. Нужно отправить в печать! Дней: {$deadline_days}";

                    $innerTask = [
                        'own_book_id' => $own_book['id'],
                        'type_id' => 2,
                        'title' => $innerTaskTitles[54],
                        'original_status' => $own_book->own_book_status['status_title'],
                        'deadline' =>  Date::parse($own_book['paid_at_print_only'])->addDays(2)
                    ];

                    if ($text_own_book_need_prints ?? null) {
                        $message_arrays[] = [
                            'title' => "🔥 *{$title}*",
                            'text' => $text_own_book_need_prints,
                            'innerTask' => $innerTask
                        ];
                    }
                }
            }


            //endregion


            $innerTasks = InnerTask::all();

            foreach ($innerTasks as $innerTask) { /* Идем по всем нашим, чтобы удалить тех, что уже нет */
                if ($innerTask['inner_task_type_id'] == 1) { /* Если Книги */
                    $search_type = 'collection_id';
                } else {
                    $search_type = 'own_book_id';
                }
                $exists = collect($message_arrays)->contains(function ($task) use ($innerTask, $search_type) { /* Ещем в сформированных сообщениях такую комбинацию */
                    return
                        isset($task['innerTask'][$search_type])
                        && $task['innerTask'][$search_type] == $innerTask[$search_type]
                        && $task['innerTask']['title'] === $innerTask['title'];
                });
                if (!$exists) { /* Если нет такого, то удаляем */
                    $innerTask->delete();
                }
            }




            $debug_mode = False;

            //region -- Посылаем Telegram уведомление нам
            if ($message_arrays) {
                if ($debug_mode) {
                    dd($message_arrays);
                } else {
                    foreach ($message_arrays as $message) {
                        if(!$manual_update) { /* Если не ручное обновление */
                            Notification::route('telegram', config('cons.telegram_chat_id'))
                                ->notify(new TelegramNotification($message['title'], $message['text'], "Админка", "vk1.com"));
                        }
                        $innerTask = $message['innerTask'] ?? null;
                        if ($innerTask) {
                            if ($innerTask['type_id'] == 1) { // Если про сборник задача, то ищем по collection_id
                                InnerTask::updateOrCreate(
                                    [
                                        'title' => $innerTask['title'], // Поля для поиска
                                        'inner_task_type_id' => $innerTask['type_id'],
                                        'collection_id' => $innerTask['col_id'],
                                    ],
                                    [
                                        'inner_task_status_id' => 1,
                                        'deadline_inner' => $innerTask['deadline']
                                    ]
                                );
                            } elseif ($innerTask['type_id'] == 2) { // Если про книгу задача, то ищем по own_book_id
                                InnerTask::updateOrCreate(
                                    [
                                        'title' => $innerTask['title'], // Поля для поиска
                                        'inner_task_type_id' => $innerTask['type_id'],
                                        'own_book_id' => $innerTask['own_book_id'],
                                    ],
                                    [
                                        'inner_task_status_id' => 1,
                                        'deadline_inner' => $innerTask['deadline']
                                    ]
                                );
                            }
                        }
                        sleep(0.5);
                    }
                }
            }
        });

        //endregion

    }
}
