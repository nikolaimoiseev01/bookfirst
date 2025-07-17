<?php

namespace Database\Seeders;

use App\Models\AlmostCompleteAction\AlmostCompleteAction;
use App\Models\Award\Award;
use App\Models\Chat\Chat;
use App\Models\Chat\Message;
use App\Models\Chat\MessageTemplate;
use App\Models\Chat\MessageTemplateType;
use App\Models\Collection\Collection;
use App\Models\Collection\CollectionNewsLetter;
use App\Models\Collection\CollectionStatus;
use App\Models\Collection\CollectionVote;
use App\Models\Collection\Participation;
use App\Models\Collection\ParticipationWork;
use App\Models\DigitalSale;
use App\Models\EmailSent;
use App\Models\InnerTask\InnerTask;
use App\Models\OwnBook\OwnBookWork;
use App\Models\PreviewComment;
use App\Models\PrintOrder\AddressType;
use App\Models\PrintOrder\LogisticCompany;
use App\Models\PrintOrder\PrintingCompany;
use App\Models\PrintOrder\PrintOrder;
use App\Models\PrintOrder\PrintOrderStatus;
use App\Models\Promocode;
use App\Models\Survey\SurveyCompleted;
use App\Models\Transaction;
use App\Models\User\User;
use App\Models\Work\Work;
use App\Models\Work\WorkLike;
use App\Services\CopyTableService;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function same_tables($test = false)
    {

        (new CopyTableService())->copy(
            sourceTable: 'users'
            , modelClass: 'App\Models\User\User'
            , columnsToExclude: ['two_factor_secret', 'two_factor_recovery_codes', 'avatar_cropped', 'avatar']
            , columnsMedia: $test ? [] : ['avatar' => 'avatar']
        );

        (new CopyTableService())->copy(
            sourceTable: 'almost_complete_action_types'
            , modelClass: 'App\Models\AlmostCompleteAction\AlmostCompleteActionType'
            , columnsToRename: ['title' => 'name']
        );

        (new CopyTableService())->copy(sourceTable: 'permissions', targetTable: 'permissions');
        (new CopyTableService())->copy(sourceTable: 'roles', targetTable: 'roles');
        (new CopyTableService())->copy(sourceTable: 'model_has_permissions', targetTable: 'model_has_permissions');
        (new CopyTableService())->copy(sourceTable: 'model_has_roles', targetTable: 'model_has_roles');
        DB::table('model_has_roles')->update(['model_type' => 'User']);
        (new CopyTableService())->copy(sourceTable: 'role_has_permissions', targetTable: 'role_has_permissions');

        (new CopyTableService())->copy(sourceTable: 'promocodes', targetTable: 'promocodes', columnsToRename: ['promocode' => 'name']);

        (new CopyTableService())->copy(sourceTable: 'inner_task_types', targetTable: 'inner_task_types');

        (new CopyTableService())->copy(
            sourceTable: 'ext_promotion_statuses'
            , targetTable: 'ext_promotion_statuses'
            , columnsToRename: ['title' => 'name']
        );

        (new CopyTableService())->copy(sourceTable: 'work_types', targetTable: 'work_types');
        (new CopyTableService())->copy(sourceTable: 'work_topics', targetTable: 'work_topics');
        (new CopyTableService())->copy(
            sourceTable: 'works'
            , modelClass: 'App\Models\Work\Work'
            , columnsToExclude: ['picture', 'picture_cropped']
            , columnsMedia: $test ? [] : ['picture' => 'image']
        );

        (new CopyTableService())->copy(
            sourceTable: 'chat_statuses'
            , modelClass: 'App\Models\Chat\ChatStatus'
            , columnsToRename: ['status' => 'name']
        );

        (new CopyTableService())->copy(
            sourceTable: 'award_types'
            , modelClass: 'App\Models\Award\AwardType'
            , columnsToExclude: ['picture']
            , columnsMedia: ['picture' => 'image']
        );

        (new CopyTableService())->copy(
            sourceTable: 'ext_promotions'
            , targetTable: 'ext_promotions'
            , columnsToExclude: ['chat_id']
        );

        (new CopyTableService())->copy(
            sourceTable: 'ext_promotion_internal_payments'
            , targetTable: 'ext_promotion_internal_payments'
        );

        (new CopyTableService())->copy(
            sourceTable: 'ext_promotion_parsed_readers'
            , targetTable: 'ext_promotion_parsed_readers'
        );

        (new CopyTableService())->copy(
            sourceTable: 'subscribers'
            , targetTable: 'email_subscriptions'
        );

        (new CopyTableService())->copy(
            sourceTable: 'user_subscriptions'
            , targetTable: 'user_x_user_subscriptions'
        );


    }

    public function make_actions()
    {
        $oldActions = DB::connection('old_mysql')->table('almost_complete_actions')->get();
        foreach ($oldActions as $oldAction) {
            if ($oldAction->collection_id ?? null) {
                $model_type = 'Collection';
                $model_id = $oldAction->collection_id;
            } else {
                $model_type = null;
                $model_id = null;
            }
            AlmostCompleteAction::create([
                'id' => $oldAction->id,
                'user_id' => $oldAction->user_id,
                'almost_complete_action_type_id' => $oldAction->almost_complete_action_type_id,
                'dt_action_completed' => $oldAction->dt_action_completed,
                'cnt_email_sent' => $oldAction->cnt_email_sent,
                'dt_last_email_sent' => $oldAction->dt_last_email_sent,
                'model_type' => $model_type,
                'model_id' => $model_id,
                'created_at' => $oldAction->created_at,
                'updated_at' => $oldAction->updated_at,
            ]);
        };
        $now_time = Carbon::now()->format('H:i:s');
        echo "almost_complete_actions OK ($now_time)\n";
    }

    public function make_survey_completeds()
    {
        $oldSurveys = DB::connection('old_mysql')->table('surveys')->get();
        foreach ($oldSurveys as $oldSurvey) {
            if ($oldSurvey->participation_id ?? null) {
                $model_type = 'Participation';
                $model_id = $oldSurvey->participation_id;
            } elseif ($oldSurvey->own_book_id) {
                $model_type = 'OwnBook';
                $model_id = $oldSurvey->own_book_id;
            }
            SurveyCompleted::create([
                'id' => $oldSurvey->id,
                'user_id' => $oldSurvey->user_id,
                'model_type' => $model_type,
                'model_id' => $model_id,
                'title' => $oldSurvey->title,
                'created_at' => $oldSurvey->created_at,
                'updated_at' => $oldSurvey->updated_at,
            ]);
        };
        $now_time = Carbon::now()->format('H:i:s');

        (new CopyTableService())->copy(
            sourceTable: 'survey_texts'
            , targetTable: 'survey_answers'
            , columnsToRename: ['survey_id' => 'survey_completed_id']
        );

        echo "surveys OK ($now_time)\n";
    }

    public function make_preview_comments()
    {
        $oldComments = DB::connection('old_mysql')->table('preview_comments')->get();
        foreach ($oldComments as $oldComment) {
            if ($oldComment->collection_id ?? null) {
                $model_type = 'Collection';
                $model_id = $oldComment->collection_id;
                $comment_type = 'inside';
            } else {
                $model_type = 'OwnBook';
                $model_id = $oldComment->own_book_id;
                $comment_type = $oldComment->own_book_comment_type;
            }
            PreviewComment::create([
                'id' => $oldComment->id,
                'user_id' => $oldComment->user_id,
                'model_type' => $model_type,
                'model_id' => $model_id,
                'comment_type' => $comment_type,
                'participation_id' => $oldComment->participation_id,
                'page' => $oldComment->page,
                'text' => $oldComment->text,
                'flg_done' => $oldComment->status_done,
                'created_at' => $oldComment->created_at,
                'updated_at' => $oldComment->updated_at,
            ]);
        };
        $now_time = Carbon::now()->format('H:i:s');
        echo "preview_comments OK ($now_time)\n";
    }

    public function make_digital_sales()
    {
        $oldSales = DB::connection('old_mysql')->table('digital_sales')->get();
        foreach ($oldSales as $oldSale) {
            if ($oldSale->bought_collection_id ?? null) {
                $model_type = 'Collection';
                $model_id = $oldSale->bought_collection_id;
            } else {
                $model_type = 'OwnBook';
                $model_id = $oldSale->bought_own_book_id;
            }
            DigitalSale::create([
                'id' => $oldSale->id,
                'user_id' => $oldSale->user_id,
                'price' => $oldSale->price * 100,
                'model_type' => $model_type,
                'model_id' => $model_id,
                'created_at' => $oldSale->created_at,
                'updated_at' => $oldSale->updated_at,
            ]);
        };
        $now_time = Carbon::now()->format('H:i:s');
        echo "digital_sales OK ($now_time)\n";
    }

    public function make_awards()
    {
        $oldAwards = DB::connection('old_mysql')->table('awards')->get();
        foreach ($oldAwards as $oldAward) {
            if ($oldAward->collection_id ?? null) {
                $model_type = 'Collection';
                $model_id = $oldAward->collection_id;
            } else {
                $model_type = 'OwnBook';
                $model_id = $oldAward->own_book_id;
            }
            Award::create([
                'id' => $oldAward->id,
                'user_id' => $oldAward->user_id,
                'award_type_id' => $oldAward->award_type_id,
                'model_type' => $model_type,
                'model_id' => $model_id,
                'created_at' => $oldAward->created_at,
                'updated_at' => $oldAward->updated_at,
            ]);
        };

        $now_time = Carbon::now()->format('H:i:s');
        echo "awards OK ($now_time)\n";
    }

    public function make_messages($test)
    {
        $chats = Chat::pluck('id')->unique()->values()->toArray();
        $oldMessages = DB::connection('old_mysql')
            ->table('messages')
            ->whereIn('chat_id', $chats)
            ->get();
        foreach ($oldMessages as $oldMessage) {
            $message = Message::create([
                'chat_id' => $oldMessage->chat_id,
                'user_id' => $oldMessage->user_from,
                'text' => $oldMessage->text
            ]);
            if (!$test) {
                $files = DB::connection('old_mysql')->table('message_files')->where('message_id', $oldMessage->id)->get();
                foreach ($files as $file) {
                    $message->addMediaFromUrl('https://pervajakniga.ru/' . $file->file)->toMediaCollection('files');
                }
            }
        }

        $now_time = Carbon::now()->format('H:i:s');
        echo "messages OK ($now_time)\n";
    }

    public function make_message_templates()
    {
        $uniqueTypes = DB::connection('old_mysql')
            ->table('message_templates')
            ->distinct()
            ->pluck('template_type');

        foreach ($uniqueTypes as $type) {
            MessageTemplateType::create([
                'name' => $type
            ]);
        }

        $oldTemplates = DB::connection('old_mysql')->table('message_templates')->get();
        foreach ($oldTemplates as $oldTemplate) {
            MessageTemplate::create([
                'message_template_type_id' => MessageTemplateType::where('name', $oldTemplate->template_type)->first()->id,
                'title' => $oldTemplate->title,
                'text' => $oldTemplate->text
            ]);
        }

        $now_time = Carbon::now()->format('H:i:s');
        echo "message_templates OK ($now_time)\n";
    }

    public function make_transactions()
    {

        $oldTransactions = DB::connection('old_mysql')->table('transactions')->get();
        foreach ($oldTransactions as $oldTransaction) {

            if ($oldTransaction->participation_id ?? null) {
                $model_type = 'Participation';
                $model_id = $oldTransaction->participation_id;
            } elseif ($oldTransaction->own_book_id) {
                $model_type = 'OwnBook';
                $model_id = $oldTransaction->own_book_id;
            } elseif ($oldTransaction->ext_promotion_id) {
                $model_type = 'ExtPromotion';
                $model_id = $oldTransaction->ext_promotion_id;
            } elseif ($oldTransaction->bought_collection_id ?? null) {
                $model_type = 'Collection';
                $model_id = $oldTransaction->bought_collection_id;
            } else {
                $model_type = null;
                $model_id = null;
            }

            if (str_contains($oldTransaction->description, 'Бронирование дополнительных печатных экземпляров (шт) сборника')) {
                $transaction_type = 'Бронирование дополнительных печатных экземпляров сборника';
            } elseif (str_contains($oldTransaction->description, 'Оплата (без печати) книги')) {
                $transaction_type = 'Оплата издания собственной книги (без печати)';
            } elseif (str_contains($oldTransaction->description, 'Оплата пересылки книги')) {
                $transaction_type = 'Оплата пересылки собственной книги';
            } elseif (str_contains($oldTransaction->description, 'Оплата пересылки сборника')) {
                $transaction_type = 'Оплата пересылки сборника';
            } elseif (str_contains($oldTransaction->description, 'Оплата печати книги')) {
                $transaction_type = 'Оплата печати собственной книги';
            } elseif (str_contains($oldTransaction->description, 'Оплата участия в сборнике')) {
                $transaction_type = 'Оплата участия в сборнике';
            } elseif (str_contains($oldTransaction->description, 'Покупка электронного варианта книги')) {
                $transaction_type = 'Покупка электронного экземпляра собственной книги';
            } elseif (str_contains($oldTransaction->description, 'Покупка электронного варианта сборника')) {
                $transaction_type = 'Покупка электронного экземпляра сборника';
            } elseif (str_contains($oldTransaction->description, 'Пополнение кошелька')) {
                $transaction_type = 'Пополнение кошелька';
            } elseif (str_contains($oldTransaction->description, 'Оплата продвижения')) {
                $transaction_type = 'Оплата продвижения';
            } else {
                $transaction_type = null;
            }

            Transaction::create([
                'id' => $oldTransaction->id,
                'status' => $oldTransaction->status,
                'user_id' => $oldTransaction->user_id,
                'description' => $oldTransaction->description,
                'amount' => $oldTransaction->amount,
                'payment_method' => $oldTransaction->payment_method,
                'transaction_type' => $transaction_type,
                'model_type' => $model_type,
                'model_id' => $model_id,
                'yoo_id' => $oldTransaction->yoo_id
            ]);
        }

        $now_time = Carbon::now()->format('H:i:s');
        echo "transactions OK ($now_time)\n";
    }

    public function make_inner_tasks()
    {
        $oldTasks = DB::connection('old_mysql')->table('inner_tasks')->get();
        foreach ($oldTasks as $oldTask) {
            if ($oldTask->collection_id ?? null) {
                $model_type = 'Collection';
                $model_id = $oldTask->collection_id;
            } elseif ($oldTask->own_book_id ?? null) {
                $model_type = 'OwnBook';
                $model_id = $oldTask->own_book_id;
            } else {
                $model_type = null;
                $model_id = null;
            }
            InnerTask::create([
                'inner_task_type_id' => $oldTask->inner_task_type_id,
                'model_type' => $model_type,
                'model_id' => $model_id,
                'responsible' => $oldTask->responsible,
                'title' => $oldTask->title,
                'description' => $oldTask->description,
                'deadline' => $oldTask->deadline,
                'deadline_inner' => $oldTask->deadline_inner,
                'flg_custom_task' => 0,
                'created_at' => $oldTask->created_at,
                'updated_at' => $oldTask->updated_at,
                'flg_custom_finished' => 0
            ]);

        };
        $now_time = Carbon::now()->format('H:i:s');
        echo "inner_tasks OK ($now_time)\n";
    }

    public function make_chats($test)
    {
        $oldChats = DB::connection('old_mysql')
            ->table('chats')
            ->get();
        foreach ($oldChats as $oldChat) {
            if ($oldChat->collection_id ?? null) {
                $model_type = 'Participation';
                $participation = DB::connection('old_mysql')
                    ->table('participations')
                    ->where('collection_id', $oldChat->collection_id)
                    ->where('user_id', $oldChat->user_created)
                    ->first();
//                dd($participation);
                if ($participation ?? null) {
                    $model_id = $participation->id;
                } else {
                    $model_id = null;
                }
            } elseif ($oldChat->own_book_id ?? null) {
                $model_type = 'OwnBook';
                $model_id = $oldChat->own_book_id;
            } elseif ($oldChat->ext_promotion_id ?? null) {
                $model_type = 'ExtPromotion';
                $model_id = $oldChat->ext_promotion_id;
            }

            if ($model_id ?? null) {
                Chat::create([
                    'id' => $oldChat->id,
                    'user_created' => $oldChat->user_created,
                    'user_to' => $oldChat->user_to,
                    'title' => $oldChat->title,
                    'chat_status_id' => $oldChat->chat_status_id,
                    'flg_admin_chat' => $oldChat->flg_admin_chat,
                    'model_type' => $model_type,
                    'model_id' => $model_id,
                    'created_at' => $oldChat->created_at,
                    'updated_at' => $oldChat->updated_at,
                ]);
            }

        };

        $now_time = Carbon::now()->format('H:i:s');
        echo "Chats OK ($now_time)\n";
    }

    public function make_print_orders()
    {

        $oldPrintOrders = DB::connection('old_mysql')
            ->table('printorders')
            ->get();

        $logistics = [
            'Почта РФ',
            'СДЭК'
        ];
        foreach ($logistics as $logistic) {
            LogisticCompany::create([
                'name' => $logistic
            ]);
        }


        $printing_companies = [
            'Канцлер',
            'Саратов',
            'BookExpert'
        ];
        foreach ($printing_companies as $printing_company) {
            PrintingCompany::create([
                'name' => $printing_company
            ]);
        }

        $print_order_statuses = [
            [
                'id' => 1,
                'name' => 'Создан',
            ],
            [
                'id' => 2,
                'name' => 'Оплачен, ждет начала печати',
            ],
            [
                'id' => 3,
                'name' => 'Идет печать',
            ],
            [
                'id' => 4,
                'name' => 'Напечатан, ждет отправки',
            ],
            [
                'id' => 9,
                'name' => 'Отправлен',
            ]
        ];
        foreach ($print_order_statuses as $print_order_status) {
            PrintOrderStatus::create($print_order_status);
        }


        foreach ($oldPrintOrders as $oldPrintOrder) {

            if ($oldPrintOrder->collection_id ?? null) {
                $model_type = 'Collection';
                $model_id = $oldPrintOrder->collection_id;
            } elseif ($oldPrintOrder->own_book_id ?? null) {
                $model_type = 'OwnBook';
                $model_id = $oldPrintOrder->own_book_id;
            };

            if (strlen($oldPrintOrder->track_number) == 11) {
                $logistic_company_id = 2; // СДЭК
            } else {
                $logistic_company_id = 1;
            }

            if ($oldPrintOrder->created_at < '2023-12-01') {
                $printing_company_id = 1; // Канцлер
            } elseif ($oldPrintOrder->created_at < '2025-03-06') {
                $printing_company_id = 2; // Саратов
            } else {
                if ($oldPrintOrder->cover_type == 'hard') {
                    $printing_company_id = 3; // BookExpert
                } else {
                    $printing_company_id = 2; // Саратов
                }
            }

            $address = collect(json_decode($oldPrintOrder->address));
            $address_type = $address['type'];
            AddressType::firstOrCreate([
                'name' => $address_type
            ]);
            if ($address['type'] == 'OLD v1') {
                $address = [
                    'string' => $address['value'],
                    'parsed_data' => null
                ];
            } elseif ($address['type'] == 'OLD v2') {
                $address = [
                    'string' => $address['value'],
                    'parsed_data' => null
                ];
            } elseif ($address['type'] == 'DaData RUS' || $address['type'] == 'foreign') {
                $address = [
                    'string' => $address['unrestricted_value'],
                    'parsed_data' => $address['data']
                ];
            }

            if ($oldPrintOrder->user_id) {
                $user_id = $oldPrintOrder->user_id;
            } else {
                $participation = Participation::where('id', $oldPrintOrder->participation_id)->first();
                if ($participation ?? null) {
                    $user_id = User::where('id', $participation['user_id']);
                } else {
                    $user_id = null;
                }

            }
            PrintOrder::create([
                'id' => $oldPrintOrder->id,
                'user_id' => $user_id,
                'model_type' => $model_type,
                'model_id' => $model_id,
                'books_cnt' => $oldPrintOrder->books_needed,
                'full_name' => $oldPrintOrder->send_to_name,
                'telephone' => $oldPrintOrder->send_to_tel,
                'inside_color' => $oldPrintOrder->inside_color == 1 ? 'Цветной' : 'Черно-белый',
                'color_pages' => $oldPrintOrder->color_pages > 0 ? $oldPrintOrder->color_pages : null,
                'cover_type' => $oldPrintOrder->cover_type == 'hard' ? 'Твердая' : 'Мягкая',
                'address' => json_encode($address),
                'country' => $oldPrintOrder->address_country,
                'address_type_id' => AddressType::where('name', $address_type)->first()->id,
                'paid_at' => $oldPrintOrder->paid_at,
                'track_number' => $oldPrintOrder->track_number,
                'logistic_company_id' => $logistic_company_id,
                'printing_company_id' => $printing_company_id,
                'created_at' => $oldPrintOrder->created_at,
                'updated_at' => $oldPrintOrder->updated_at,
            ]);
        }

        $now_time = Carbon::now()->format('H:i:s');
        echo "printorders OK ($now_time)\n";
    }


    public function make_collections($test = false)
    {

        $oldCollections = DB::connection('old_mysql')
            ->table('collections')
            ->get();
        foreach ($oldCollections as $collection) {

            $wordsToRemove = ['Современный ', ' Поэзии', 'Сокровенные ', 'Выпуск '];
            $short_nm = str_replace($wordsToRemove, '', $collection->title);
            $slug = Str::slug($short_nm);

            $winners = DB::connection('old_mysql')
                ->table('collection_winners')
                ->where('collection_id', $collection->id)
                ->orderBy('place', 'asc')
                ->pluck('user_id')
                ->toArray();

            $links = null;
            if ($collection->amazon_link) {
                $links = [
                    'amazon' => $collection->amazon_link
                ];
            }
//            dd(json_encode($winners));

            $new_collection = Collection::firstOrCreate(
                ['name' => $collection->title],
                [
                    'id' => $collection->id,
                    'name_short' => $short_nm,
                    'slug' => $slug,
                    'collection_status_id' => $collection->col_status_id,
                    'description' => $collection->col_desc,
                    'date_apps_end' => $collection->col_date1,
                    'date_preview' => $collection->col_date2,
                    'date_voting_end' => $collection->col_date3,
                    'date_print_start' => $collection->col_date3,
                    'date_print_end' => $collection->col_date4,
                    'created_at' => $collection->created_at,
                    'updated_at' => $collection->updated_at,
                    'winner_participations' => $winners,
                    'links' => $links,
                ]);
            if ($new_collection->wasRecentlyCreated && !$test) {
                $inside_url = 'https://pervajakniga.ru/' . $collection->pre_var;
                $cover_2d_url = 'https://pervajakniga.ru/' . $collection->cover_2d;
                $cover_3d_url = 'https://pervajakniga.ru/' . $collection->cover_3d;
                try {
                    $new_collection->addMediaFromUrl($inside_url)
                        ->usingFileName("$slug.pdf")      // имя файла на диске
                        ->usingName($slug)
                        ->preservingOriginal()
                        ->toMediaCollection('inside_file');
                } catch (\Throwable $th) {
                    // 1. Декодируем URL
                    $decoded = urldecode($inside_url);
                    // 2. Заменяем "й" (один символ) на "и" + "◌̆" (диакритика)
                    $replaced = str_replace('й', "и\u{0306}", $decoded);
                    // 3. Снова кодируем в URL
                    $encoded = rawurlencode($replaced);
                    // 4. Восстанавливаем остальные части URL (чтобы не кодировались двоеточия, слэши и т.п.)
                    $finalUrl = str_replace(
                        ['%3A', '%2F'],
                        [':', '/'],
                        $encoded
                    );
                    try {
                        $new_collection->addMediaFromUrl($finalUrl)
                            ->usingFileName("$slug.pdf")      // имя файла на диске
                            ->usingName($slug)
                            ->preservingOriginal()
                            ->toMediaCollection('inside_file');
                    } catch (\Throwable $th) {

                    }
                }
                try {
                    $new_collection->addMediaFromUrl($cover_2d_url)
                        ->preservingOriginal()
                        ->toMediaCollection('cover_2d');
                } catch (\Throwable $th) {
                    try {
                        $new_collection->addMediaFromUrl($cover_3d_url)
                            ->preservingOriginal()
                            ->toMediaCollection('cover_3d');
                    } catch (\Throwable $th) {
                    }
                }
            }
        }
    }

    public function make_collection_statuses()
    {
        $statuses = [
            [
                'id' => 1,
                'name' => 'Идет прием заявок',
            ],
            [
                'id' => 2,
                'name' => 'Предварительная проверка',
            ],
            [
                'id' => 3,
                'name' => 'Подготовка к печати',
            ],
            [
                'id' => 4,
                'name' => 'Идет печать',
            ],
            [
                'id' => 9,
                'name' => 'Сборник издан',
            ],
        ];
        foreach ($statuses as $status) {
            CollectionStatus::create($status);
        }
    }

    public function make_collection_votes($test)
    {

        $oldCollectionVotes = DB::connection('old_mysql')
            ->table('votes')
            ->get();
        foreach ($oldCollectionVotes as $oldCollectionVote) {
            $participation_id_from = DB::connection('old_mysql')
                ->table('participations')
                ->where('user_id', $oldCollectionVote->user_id_from)
                ->where('collection_id', $oldCollectionVote->collection_id)
                ->first();
            $participation_id_to = DB::connection('old_mysql')
                ->table('participations')
                ->where('user_id', $oldCollectionVote->user_id_to)
                ->where('collection_id', $oldCollectionVote->collection_id)
                ->first();
            if ($participation_id_from && $participation_id_to) {
                CollectionVote::create([
                    'participation_id_from' => $participation_id_from->id,
                    'participation_id_to' => $participation_id_to->id,
                    'collection_id' => $oldCollectionVote->collection_id
                ]);
            }
        }
    }

    public function make_news_letters($test)
    {
        $users_we_have = User::pluck('id')->unique()->values()->toArray();
        $oldEmailSents = DB::connection('old_mysql')
            ->table('email_sents')
            ->get();
        foreach ($oldEmailSents as $emailSent) {
            $users = array_map('intval', explode(';', $emailSent->sent_to_user));
            $users = array_intersect($users, $users_we_have);
            $users = array_values($users);
            if ($emailSent->subject != 'Требуется действие') {
                CollectionNewsLetter::create([
                    'collection_id' => $emailSent->collection_id,
                    'subject' => $emailSent->subject,
                    'text' => $emailSent->email_text,
                    'users' => json_encode($users)
                ]);
            }
            if (count($users) > 0) {
                foreach ($users as $user) {
                    EmailSent::create([
                        'user_id' => $user,
                        'subject' => $emailSent->subject,
                        'text' => $emailSent->email_text
                    ]);
                }
            }
        }
    }


    public function make_participations($test)
    {
        $oldParticipations = DB::connection('old_mysql')
            ->table('participations')
            ->get();
        foreach ($oldParticipations as $participation) {

            if ($participation->nickname) {
                $author_name = $participation->nickname;
            } else {
                $author_name = $participation->name . ' ' . $participation->surname;
            }
            $promocode_id = Promocode::where('name', $participation->promocode)->first()['id'] ?? null;

            Participation::create([
                'id' => $participation->id,
                'collection_id' => $participation->collection_id,
                'user_id' => $participation->user_id,
                'author_name' => $author_name,
                'works_number' => $participation->works_number,
                'rows' => $participation->rows,
                'pages' => $participation->pages,
                'participation_status_id' => $participation->pat_status_id,
                'print_order_id' => $participation->printorder_id,
                'promocode_id' => $promocode_id,
                'price_part' => $participation->part_price,
                'price_print' => $participation->print_price,
                'price_check' => $participation->check_price,
                'price_send' => $participation->send_price,
                'price_total' => $participation->total_price
            ]);
        }
    }


    public function make_participation_works($test)
    {
        $works = Work::pluck('id')->unique()->values()->toArray();
        $participations = Participation::pluck('id')->unique()->values()->toArray();
        $oldParticipationWorks = DB::connection('old_mysql')
            ->table('participation_works')
            ->whereIn('work_id', $works)
            ->whereIn('participation_id', $participations)
            ->get();
        foreach ($oldParticipationWorks as $oldParticipationWork) {
            ParticipationWork::create([
                'work_id' => $oldParticipationWork->work_id,
                'participation_id' => $oldParticipationWork->participation_id
            ]);
        }
    }


    public function make_work_likes()
    {
        $works = Work::pluck('id')->unique()->values()->toArray();
        $users = User::pluck('id')->unique()->values()->toArray();
        $oldWorkLikes = DB::connection('old_mysql')
            ->table('work_likes')
            ->whereIn('work_id', $works)
            ->whereIn('user_id', $users)
            ->get();
        foreach ($oldWorkLikes as $oldWorkLike) {
            WorkLike::create([
                'user_id' => $oldWorkLike->user_id,
                'work_id' => $oldWorkLike->work_id
            ]);
        }
    }


    public function run(): void
    {
        $test = True;

        $file = new Filesystem;
        $file->cleanDirectory(storage_path('app/public/media'));
//
        $this->same_tables(test: $test);

        $this->make_survey_completeds();
        $this->make_inner_tasks();
        $this->make_chats($test);
        $this->make_messages($test);
        $this->make_awards();
        $this->make_actions();
        $this->make_digital_sales();
        $this->make_message_templates();
        $this->make_preview_comments();
        $this->make_print_orders();
        $this->make_transactions();
        $this->make_work_likes();


        $now_time = Carbon::now()->format('H:i:s');
        echo "Collections START ($now_time)\n";
        $this->make_collection_statuses();
        (new CopyTableService())->copy(sourceTable: 'pat_statuses', targetTable: 'participation_statuses', columnsToRename: ['pat_status_title' => 'name']);
        $this->make_collections($test);
        $this->make_participations($test);
        $this->make_news_letters($test);
        $this->make_collection_votes($test);
        $this->make_participation_works($test);
        $now_time = Carbon::now()->format('H:i:s');
        echo "Collections END ($now_time)\n";

        (new OwnBookSeeder())->run(test: $test);
    }
}
