<?php

namespace Database\Seeders;

use App\Enums\CollectionStatusEnums;
use App\Enums\PrintOrderStatusEnums;
use App\Enums\PrintOrderTypeEnums;
use App\Enums\TransactionTypeEnums;
use App\Models\AlmostCompleteAction;
use App\Models\Award\Award;
use App\Models\Chat\Chat;
use App\Models\Chat\Message;
use App\Models\Chat\MessageTemplate;
use App\Models\Collection\Collection;
use App\Models\Collection\CollectionNewsLetter;
use App\Models\Collection\CollectionVote;
use App\Models\Collection\Participation;
use App\Models\Collection\ParticipationWork;
use App\Models\DigitalSale;
use App\Models\EmailSent;
use App\Models\ExtPromotion\ExtPromotion;
use App\Models\OwnBook\OwnBookCoverStatus;
use App\Models\OwnBook\OwnBookInsideStatus;
use App\Models\OwnBook\OwnBookStatus;
use App\Models\PreviewComment;
use App\Models\PrintOrder\LogisticCompany;
use App\Models\PrintOrder\PrintingCompany;
use App\Models\PrintOrder\PrintOrder;
use App\Models\Promocode;
use App\Models\Survey\SurveyCompleted;
use App\Models\Transaction;
use App\Models\User\User;
use App\Models\Work\Work;
use App\Models\Work\WorkLike;
use App\Models\Work\WorkTopic;
use App\Models\Work\WorkType;
use App\Services\CopyTableService;
use App\Services\PdfService;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function same_tables($test = false)
    {

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

        (new CopyTableService())->copy(sourceTable: 'work_types', targetTable: 'work_types');
        (new CopyTableService())->copy(sourceTable: 'work_topics', targetTable: 'work_topics');
        (new CopyTableService())->copy(sourceTable: 'work_comments', targetTable: 'work_comments', columnsToExclude: ['parent_comment_id', 'reply_to_comment_id', 'reply_to_user_id']);
        (new CopyTableService())->copy(
            sourceTable: 'works'
            , modelClass: 'App\Models\Work\Work'
            , columnsToExclude: ['picture', 'picture_cropped']
            , columnsMedia: $test ? [] : ['picture' => 'image']
        );

        (new CopyTableService())->copy(
            sourceTable: 'award_types'
            , modelClass: 'App\Models\Award\AwardType'
            , columnsToExclude: ['picture']
            , columnsMedia: ['picture' => 'image']
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

    public function make_ext_promotions()
    {
        $oldExtPromotions = DB::connection('old_mysql')->table('ext_promotions')->get();
        foreach ($oldExtPromotions as $oldExtPromotion) {
            $status = DB::connection('old_mysql')
                ->table('ext_promotion_statuses')
                ->where('id', $oldExtPromotion->ext_promotion_status_id)->first()->title;
            ExtPromotion::create([
                'id' => $oldExtPromotion->id,
                'user_id' => $oldExtPromotion->user_id,
                'status' => $status,
                'login' => $oldExtPromotion->login,
                'password' => $oldExtPromotion->password,
                'site' => $oldExtPromotion->site,
                'days' => $oldExtPromotion->days,
                'price_total' => $oldExtPromotion->price_total,
                'price_executor' => $oldExtPromotion->price_executor,
                'price_our' => $oldExtPromotion->price_our,
                'promocode_id' => $oldExtPromotion->promocode_id,
                'paid_at' => $oldExtPromotion->paid_at,
                'started_at' => $oldExtPromotion->started_at,
                'comment' => $oldExtPromotion->comment,
                'executor_got_payment' => $oldExtPromotion->executor_got_payment,
            ]);
        };
        $now_time = Carbon::now()->format('H:i:s');
        echo "preview_comments OK ($now_time)\n";

        (new CopyTableService())->copy(
            sourceTable: 'ext_promotion_internal_payments'
            , targetTable: 'ext_promotion_internal_payments'
        );

        (new CopyTableService())->copy(
            sourceTable: 'ext_promotion_parsed_readers'
            , targetTable: 'ext_promotion_parsed_readers'
        );
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
                'price' => $oldSale->price,
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
                'id' => $oldMessage->id,
                'chat_id' => $oldMessage->chat_id,
                'user_id' => $oldMessage->user_from,
                'text' => $oldMessage->text,
                'created_at' => $oldMessage->created_at,
                'updated_at' => $oldMessage->updated_at
            ]);
            if (!$test) {
                $files = DB::connection('old_mysql')->table('message_files')->where('message_id', $oldMessage->id)->get();
                foreach ($files as $file) {
                    try {
                        $message
                            ->addMediaFromUrl('https://pervajakniga.ru/' . $file->file)
                            ->toMediaCollection('files');
                    } catch (FileIsTooBig $e) {
                        logger()->warning("Файл слишком большой и пропущен: " . $file->file);
                    } catch (\Throwable $e) {
                        // Любая другая ошибка — пробрасываем дальше
                        throw $e;
                    }
                }
            }
        }

        $now_time = Carbon::now()->format('H:i:s');
        echo "messages OK ($now_time)\n";
    }

    public function make_message_templates()
    {
        $oldTemplates = DB::connection('old_mysql')->table('message_templates')->get();
        foreach ($oldTemplates as $oldTemplate) {
            MessageTemplate::create([
                'id' => $oldTemplate->id,
                'type' => $oldTemplate->template_type,
                'title' => $oldTemplate->title,
                'text' => $oldTemplate->text
            ]);
        }

        $now_time = Carbon::now()->format('H:i:s');
        echo "message_templates OK ($now_time)\n";
    }

    public function make_transactions(): void
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

            if (str_contains($oldTransaction->description, 'Оплата участия в сборнике')) {
                $transaction_type = TransactionTypeEnums::COLLECTION_PARTICIPATION;
            } elseif (str_contains($oldTransaction->description, 'Оплата пересылки сборника')) {
                $transaction_type = TransactionTypeEnums::COLLECTION_SHIPPING;
            } elseif (str_contains($oldTransaction->description, 'Бронирование дополнительных печатных экземпляров (шт) сборника')) {
                $transaction_type = TransactionTypeEnums::COLLECTION_ADDITIONAL_RESERVATION;
            } elseif (str_contains($oldTransaction->description, 'Покупка электронного варианта сборника')) {
                $transaction_type = TransactionTypeEnums::COLLECTION_EBOOK_PURCHASE;
            } elseif (str_contains($oldTransaction->description, 'Оплата (без печати) книги')) {
                $transaction_type = TransactionTypeEnums::OWN_BOOK_WO_PRINT;
            } elseif (str_contains($oldTransaction->description, 'Оплата печати книги')) {
                $transaction_type = TransactionTypeEnums::OWN_BOOK_PRINT;
            } elseif (str_contains($oldTransaction->description, 'Оплата пересылки книги')) {
                $transaction_type = TransactionTypeEnums::OWN_BOOK_SHIPPING;
            } elseif (str_contains($oldTransaction->description, 'Покупка электронного варианта книги')) {
                $transaction_type = TransactionTypeEnums::OWN_BOOK_EBOOK_PURCHASE;
            } elseif (str_contains($oldTransaction->description, 'Пополнение кошелька')) {
                $transaction_type = TransactionTypeEnums::WALLET_TOP_UP;
            } elseif (str_contains($oldTransaction->description, 'Оплата продвижения')) {
                $transaction_type = TransactionTypeEnums::EXT_PROMOTION_PAYMENT;
            } else {
                $transaction_type = null;
            }

            Transaction::create([
                'id' => $oldTransaction->id,
                'status' => $oldTransaction->status,
                'user_id' => $oldTransaction->user_id,
                'amount' => $oldTransaction->amount,
                'payment_method' => $oldTransaction->payment_method,
                'description' => $oldTransaction->description,
                'type' => $transaction_type,
                'model_type' => $model_type,
                'model_id' => $model_id,
                'yoo_id' => $oldTransaction->yoo_id,
                'created_at' => $oldTransaction->created_at,
                'updated_at' => $oldTransaction->updated_at
            ]);
        }

        $now_time = Carbon::now()->format('H:i:s');
        echo "transactions OK ($now_time)\n";
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

            $status = DB::connection('old_mysql')
                ->table('chat_statuses')
                ->where('id', $oldChat->chat_status_id)
                ->first()->status;

            if ($model_id ?? null) {
                Chat::create([
                    'id' => $oldChat->id,
                    'user_created' => $oldChat->user_created,
                    'user_to' => $oldChat->user_to,
                    'title' => $oldChat->title,
                    'status' => $status,
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

    /** @noinspection D */
    public function make_print_orders()
    {

        $oldPrintOrders = DB::connection('old_mysql')
            ->table('printorders')
            ->get();

        $logistics = [
            [
                'name' => 'Почта РФ',
                'base_tracking_link' => 'https://www.pochta.ru/tracking?barcode='
            ],
            [
                'name' => 'СДЭК',
                'base_tracking_link' => 'https://www.cdek.ru/ru/tracking/?order_id='
            ]
        ];
        foreach ($logistics as $logistic) {
            LogisticCompany::create([
                'name' => $logistic['name'],
                'base_tracking_link' => $logistic['base_tracking_link']
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

        foreach ($oldPrintOrders as $oldPrintOrder) {

            if ($oldPrintOrder->collection_id ?? null) {
                $model_type = 'Collection';
                $type = PrintOrderTypeEnums::COLLECTION_PARTICIPATION;
                $model_id = $oldPrintOrder->collection_id;
            } elseif ($oldPrintOrder->own_book_id ?? null) {
                $model_type = 'OwnBook';
                $type = PrintOrderTypeEnums::OWN_BOOK_PUBLISH;
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
            $addressType = $address['type'];
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
            $oldParticipation = DB::connection('old_mysql')
                ->table('participations')
                ->where('printorder_id', $oldPrintOrder->id)
                ->first();
            $oldOwnBook = DB::connection('old_mysql')
                ->table('own_books')
                ->where('id', $oldPrintOrder->own_book_id)
                ->first();
            if ($oldParticipation ?? null) {
                $pricePrint = $oldParticipation->print_price;
                $priceSend = $oldParticipation->send_price;

            } else if ($oldOwnBook ?? null) {
                $pricePrint = $oldOwnBook->print_price;
                $priceSend = $oldPrintOrder->send_price;
            } else {
                $pricePrint = null;
                $priceSend = null;
            }

            PrintOrder::create([
                'id' => $oldPrintOrder->id,
                'type' => $type,
                'user_id' => $user_id,
                'model_type' => $model_type,
                'model_id' => $model_id,
                'books_cnt' => $oldPrintOrder->books_needed,
                'receiver_name' => $oldPrintOrder->send_to_name,
                'receiver_telephone' => $oldPrintOrder->send_to_tel,
                'inside_color' => $oldPrintOrder->inside_color == 1 ? 'Цветной' : 'Черно-белый',
                'pages_color' => $oldPrintOrder->color_pages > 0 ? $oldPrintOrder->color_pages : null,
                'cover_type' => $oldPrintOrder->cover_type == 'hard' ? 'Твердая' : 'Мягкая',
                'address_json' => $address,
                'country' => $oldPrintOrder->address_country,
                'price_print' => $pricePrint,
                'price_send' => $priceSend,
                'address_type' => $addressType,
                'paid_at' => $oldPrintOrder->paid_at,
                'track_number' => $oldPrintOrder->track_number,
                'logistic_company_id' => $logistic_company_id,
                'printing_company_id' => $printing_company_id,
                'created_at' => $oldPrintOrder->created_at,
                'updated_at' => $oldPrintOrder->updated_at,
                'status' => PrintOrderStatusEnums::SENT
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
            if (str_contains($short_nm, 'Дух')) {
                $work_type_id = 1;
            } else {
                $work_type_id = 2;
            }
            $slug = Str::slug($short_nm);

            $winners = DB::connection('old_mysql')
                ->table('collection_winners')
                ->where('collection_id', $collection->id)
                ->orderBy('place', 'asc')
                ->pluck('participation_id')
                ->toArray();

            $links = null;
            if ($collection->amazon_link) {
                $links = [
                    'platform' => 'amazon',
                    'link' => $collection->amazon_link
                ];
            }

            $status = DB::connection('old_mysql')
                ->table('col_statuses')
                ->where('id', $collection->col_status_id)
                ->first()->col_status;

            $new_collection = Collection::firstOrCreate(
                ['title' => $collection->title],
                [
                    'id' => $collection->id,
                    'title_short' => $short_nm,
                    'slug' => $slug,
                    'status' => $status,
                    'description' => $collection->col_desc,
                    'date_apps_end' => $collection->col_date1,
                    'date_preview_start' => $collection->col_date2,
                    'date_preview_end' => $collection->col_date3,
                    'date_print_start' => $collection->col_date3,
                    'date_print_end' => $collection->col_date4,
                    'created_at' => $collection->created_at,
                    'updated_at' => $collection->updated_at,
                    'winner_participations' => $winners,
                    'selling_links' => $links ?? null,
                    'work_type_id' => $work_type_id
                ]);
            if ($new_collection->wasRecentlyCreated) {
                $inside_url = 'https://pervajakniga.ru/' . $collection->pre_var;
                $cover_2d_url = 'https://pervajakniga.ru/' . $collection->cover_2d;
                $cover_3d_url = 'https://pervajakniga.ru/' . $collection->cover_3d;
                try {
                    if ($collection->pre_var) {
                        $new_collection->addMediaFromUrl($inside_url)
                            ->usingFileName("$slug.pdf") // имя файла на диске
                            ->usingName($slug)
                            ->preservingOriginal()
                            ->toMediaCollection('inside_file');
                    }
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
                        ->toMediaCollection('cover_front');
                } catch (\Throwable $th) {
                    try {
                        $new_collection->addMediaFromUrl($cover_3d_url)
                            ->preservingOriginal()
                            ->toMediaCollection('cover_3d');
                    } catch (\Throwable $th) {
                    }
                }
                if ($collection->pre_var) {
                    try {
                        $preview_file_name = $new_collection['slug'] . '_preview.pdf';
                        $media = $new_collection->getFirstMedia('inside_file');
                        $path = $media->getPath(); // абсолютный путь в storage
                        app(PdfService::class)
                            ->cutAndAttach($new_collection, $path, 10, 'inside_file_preview', $preview_file_name);
                    } catch (\Throwable $th) {

                    }
                }
            }
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
                    'collection_id' => $oldCollectionVote->collection_id,
                    'created_at' => $oldCollectionVote->created_at,
                    'updated_at' => $oldCollectionVote->updated_at
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
                    'users' => json_encode($users),
                    'created_at' => $emailSent->created_at,
                    'updated_at' => $emailSent->updated_at
                ]);
            }
            if (count($users) > 0) {
                foreach ($users as $user) {
                    EmailSent::create([
                        'user_id' => $user,
                        'subject' => $emailSent->subject,
                        'text' => $emailSent->email_text,
                        'created_at' => $emailSent->created_at,
                        'updated_at' => $emailSent->updated_at
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
            $status = DB::connection('old_mysql')
                ->table('pat_statuses')
                ->where('id', $participation->pat_status_id)
                ->first()->pat_status_title;
            Participation::create([
                'id' => $participation->id,
                'collection_id' => $participation->collection_id,
                'user_id' => $participation->user_id,
                'author_name' => $author_name,
                'works_number' => $participation->works_number,
                'rows' => $participation->rows,
                'pages' => $participation->pages,
                'status' => $status,
                'print_order_id' => $participation->printorder_id,
                'promocode_id' => $promocode_id,
                'price_part' => $participation->part_price,
                'price_check' => $participation->check_price,
                'price_total' => $participation->total_price - $participation->print_price,
                'created_at' => $participation->created_at,
                'updated_at' => $participation->updated_at
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
                'id' => $oldParticipationWork->id,
                'work_id' => $oldParticipationWork->work_id,
                'participation_id' => $oldParticipationWork->participation_id,
                'created_at' => $oldParticipationWork->created_at,
                'updated_at' => $oldParticipationWork->updated_at
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
                'work_id' => $oldWorkLike->work_id,
                'created_at' => $oldWorkLike->created_at,
                'updated_at' => $oldWorkLike->updated_at
            ]);
        }
    }

    public function testNewData()
    {
        DB::transaction(function () {

            $role = DB::table('roles')->insert([
                'name' => 'admin',
                'guard_name' => 'admin',
                'public_name' => 'admin'
            ]);
            $role_id = DB::table('roles')->first()->id;
            User::firstOrCreate([
                'name' => 'Admin Name',
                'surname' => 'Admin Surname',
                'email' => 'admin@mail.ru',
                'email_verified_at' => now(),
                'password' => Hash::make('12345678')
            ]);
            $user_id = DB::table('users')->first()->id;
            DB::table('model_has_roles')->insert([
                'role_id' => $role_id,
                'model_type' => 'User',
                'model_id' => $user_id
            ]);

            Collection::create([
                'title' => 'Современный Дух Поэзии. Выпуск 60',
                'title_short' => 'ДУХ 60',
                'slug' => 'duh-60',
                'status' => CollectionStatusEnums::APPS_IN_PROGRESS
            ]);

            Promocode::create([
                'name' => 'XYZ',
                'discount' => 20
            ]);


            $workTypes = [
                'Поэзия',
                'Стихи'
            ];
            foreach ($workTypes as $workType) {
                WorkType::create([
                    'name' => $workType,
                ]);
            }

            $workTopics = [
                'Лирика',
                'Пейзажная'
            ];
            foreach ($workTopics as $workTopic) {
                WorkTopic::create([
                    'name' => $workTopic,
                ]);
            }

        });

    }


    public function run(): void
    {
        $test = False;

        $file = new Filesystem;
        $file->cleanDirectory(storage_path('app/public/media'));

        $this->testNewData();
//        (new CopyTableService())->copy(
//            sourceTable: 'users'
//            , modelClass: 'App\Models\User\User'
//            , columnsToExclude: ['two_factor_secret', 'two_factor_recovery_codes', 'avatar_cropped', 'avatar']
//            , columnsMedia: $test ? [] : ['avatar' => 'avatar']
//        );
//
//        $this->same_tables(test: $test);
//
//        $this->make_ext_promotions();
//        $this->make_survey_completeds();
//        $this->make_chats($test);
//        $this->make_messages($test);
//        $this->make_awards();
//        $this->make_actions();
//        $this->make_digital_sales();
//        $this->make_message_templates();
//        $this->make_preview_comments();
//        $this->make_print_orders();
//        $this->make_transactions();
//        $this->make_work_likes();
//
//        $now_time = Carbon::now()->format('H:i:s');
//        echo "Collections START ($now_time)\n";
//        $this->make_collections($test);
//        $this->make_participations($test);
//        $this->make_news_letters($test);
//        $this->make_collection_votes($test);
//        $this->make_participation_works($test);
//        $now_time = Carbon::now()->format('H:i:s');
//        echo "Collections END ($now_time)\n";
//
//        (new OwnBookSeeder())->run(test: $test);
    }
}
