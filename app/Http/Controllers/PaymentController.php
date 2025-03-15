<?php

namespace App\Http\Controllers;

use App\Enums\PaymentStatusEnum;
use App\Models\award;
use App\Models\Collection;
use App\Models\digital_sale;
use App\Models\ext_promotion;
use App\Models\own_book;
use App\Models\Participation;
use App\Models\Printorder;
use App\Models\Transaction;
use App\Models\User;
use App\Models\UserWallet;
use App\Notifications\EmailNotification;
use App\Notifications\new_participation;
use App\Notifications\TelegramNotification;
use App\Service\DangerTasksService;
use App\Service\PaymentService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Jenssegers\Date\Date;
use YooKassa\Model\Notification\NotificationSucceeded;
use YooKassa\Model\Notification\NotificationWaitingForCapture;
use YooKassa\Model\NotificationEventType;

class PaymentController extends Controller
{
    public $url_redirect;

    public function create_part_payment($participation_id, $amount, PaymentService $service)
    {


        $description = "Оплата участия в сборнике '" . Collection::where('id', Participation::where('id', $participation_id)->value('collection_id'))->value('title') . "'";
        $url_redirect = url()->previous();

        // Записываем данные транзакции
        $transaction = new Transaction();
        $transaction->user_id = Auth::user()->id;
        $transaction->amount = $amount;
        $transaction->participation_id = $participation_id;
        $transaction->description = $description;
        $transaction->save();

        if ($transaction) {
            $link = $service->createPayment($amount, $description, $url_redirect, [
                'transaction_id' => $transaction->id,
                'user_id' => Auth::user()->id,
                'participation_id' => $participation_id,
                'own_book_id' => null,
                'url_redirect' => $url_redirect
            ]);

            if (isset($link)) {
                return redirect()->away($link);
            }
        }
    }


    public function create_send_payment($print_id, $amount, PaymentService $service)
    {

        $collection_id = Printorder::where('id', $print_id)->value('collection_id') ?? null;
        $participation_id = Participation::where('printorder_id', $print_id)->value('id') ?? null;
        $own_book_id = Printorder::where('id', $print_id)->value('own_book_id') ?? null;

        if ($collection_id > 0) { // Это оплата за пересылку сборника
            $description = "Оплата пересылки сборника '" . Collection::where('id', $collection_id)->value('title') . "'";
        }

        if ($own_book_id > 0) { // Это оплата за пересылку книги
            $description = "Оплата пересылки книги '" . own_book::where('id', $own_book_id)->value('title') . "'";
        }

        $url_redirect = url()->previous();

        // Записываем данные транзакции
        $transaction = new Transaction();
        $transaction->user_id = Auth::user()->id;
        $transaction->amount = $amount;
        $transaction->participation_id = $participation_id;
        $transaction->own_book_id = $own_book_id;
        $transaction->print_id = $print_id;
        $transaction->description = $description;
        $transaction->save();

        if ($transaction) {
            $link = $service->createPayment($amount, $description, $url_redirect, [
                'transaction_id' => $transaction->id,
                'user_id' => Auth::user()->id,
                'print_id' => $print_id,
                'participation_id' => $collection_id,
                'own_book_id' => $own_book_id,
                'url_redirect' => $url_redirect
            ]);

            if (isset($link)) {
                return redirect()->away($link);
            }
        }
    }

    public function create_own_book_payment($own_book_id, $payment_type, $amount, PaymentService $service)
    {

        $own_book = own_book::where('id', $own_book_id)->first();

        if ($payment_type === 'Without_Print') {
            $own_book_payment_text = '(без печати)';
        } else {
            $own_book_payment_text = "печати";
        };


        $description = "Оплата " . $own_book_payment_text . " книги '" . $own_book['title'] . "'";
        $url_redirect = url()->previous();

        // Записываем данные транзакции
        $transaction = new Transaction();
        $transaction->user_id = Auth::user()->id;
        $transaction->amount = $amount;
        $transaction->own_book_id = $own_book['id'];
        $transaction->own_book_payment_type = $payment_type;
        $transaction->description = $description;
        $transaction->save();

        if ($transaction) {
            $link = $service->createPayment($amount, $description, $url_redirect, [
                'transaction_id' => $transaction->id,
                'user_id' => Auth::user()->id,
                'participation_id' => null,
                'own_book_id' => $own_book['id'],
                'own_book_payment_type' => $payment_type,
                'url_redirect' => $url_redirect
            ]);

            if (isset($link)) {
                return redirect()->away($link);
            }
        }

    }


    public function create_buying_collection($collection_id, PaymentService $service)
    {

        $collection = Collection::where('id', $collection_id)->first();

        $description = "Покупка электронного варианта сборника '" . $collection['title'] . "'";
        $url_redirect = route('my_digital_sales');

        // Записываем данные транзакции
        $transaction = new Transaction();
        $transaction->user_id = Auth::user()->id;
        $transaction->amount = 100;
        $transaction->bought_collection_id = $collection_id;
        $transaction->description = $description;
        $transaction->save();

        if ($transaction) {
            $link = $service->createPayment(100, $description, $url_redirect, [
                'user_id' => Auth::user()->id,
                'transaction_id' => $transaction->id,
                'bought_collection_id' => $collection_id,
                'own_book_id' => null,
                'url_redirect' => $url_redirect
            ]);

            if (isset($link)) {
                return redirect()->away($link);
            }
        }
    }

    public function create_buying_own_book($own_book_id, PaymentService $service)
    {

        $own_book = own_book::where('id', $own_book_id)->first();

        $description = "Покупка электронного варианта книги '" . $own_book['title'] . "'";
        $url_redirect = route('my_digital_sales');

        // Записываем данные транзакции
        $transaction = new Transaction();
        $transaction->user_id = Auth::user()->id;
        $transaction->amount = 100;
        $transaction->bought_own_book_id = $own_book_id;
        $transaction->description = $description;
        $transaction->save();

        if ($transaction) {
            $link = $service->createPayment(100, $description, $url_redirect, [
                'user_id' => Auth::user()->id,
                'transaction_id' => $transaction->id,
                'bought_own_book_id' => $own_book_id,
                'own_book_id' => null,
                'url_redirect' => $url_redirect
            ]);

            if (isset($link)) {
                return redirect()->away($link);
            }
        }
    }

    public function create_points_payment(Request $request, PaymentService $service)
    {

        $user_id = Auth::user()->id;
        $amount = $request->amount;
        $description = "Пополнение кошелька";
        $url_redirect = $request->url_redirect;

        // Записываем данные транзакции
        $transaction = new Transaction();
        $transaction->user_id = $user_id;
        $transaction->amount = $amount;
        $transaction->bought_own_book_id = null;
        $transaction->description = $description;
        $transaction->save();

        if ($transaction) {
            $link = $service->createPayment($amount, $description, $url_redirect, [
                'user_id' => Auth::user()->id,
                'transaction_id' => $transaction->id,
                'amount' => $amount,
                'description' => $description,
                'url_redirect' => $url_redirect
            ]);

            if (isset($link)) {
                return redirect()->away($link);
            }
        }
    }

    public function create_ext_promotion_payment($ext_promotion_id, $amount, Request $request, PaymentService $service)
    {


//        DB::transaction(function () use ($request, $ext_promotion_id, $amount, $service) { // Чтобы не записать ненужного
            $user_id = Auth::user()->id;
            $amount = $request->amount;
            $description = "Оплата продвижения";
            $url_redirect = url()->previous();

            // Записываем данные транзакции
            $transaction = new Transaction();
            $transaction->user_id = $user_id;
            $transaction->amount = $amount;
            $transaction->bought_own_book_id = null;
            $transaction->ext_promotion_id = $ext_promotion_id;
            $transaction->description = $description;
            $transaction->save();

            if ($transaction) {
                $link = $service->createPayment($amount, $description, $url_redirect, [
                    'user_id' => Auth::user()->id,
                    'transaction_id' => $transaction->id,
                    'amount' => $amount,
                    'ext_promotion_id' => $ext_promotion_id,
                    'description' => $description,
                    'url_redirect' => $url_redirect
                ]);

                if (isset($link)) {
                    return redirect()->away($link);
                }
            }
//        });
    }


    public function callback(Request $request, PaymentService $service)
    {


        App::setLocale('ru');

        // Получите данные из POST-запроса от ЮKassa
        $source = file_get_contents('php://input');

        $requestBody = json_decode($source, true);


        $notification = $requestBody['object'];


        // Общая информация о транзакции
        // Добавляем ID от YOOKASSA
        $metadata = $notification['metadata'];

        $transactionId = (int)$metadata['transaction_id'];
        if (Transaction::where('id', $transactionId)->value('yoo_id') === null) {

            Transaction::where('id', $transactionId)
                ->update(array(
                    'yoo_id' => $notification['payment_method']['id'],
                ));

        }
        // -----------------------------------------------------------------


        if (isset($notification['status']) && $notification['status'] === 'succeeded') { // Если операция прошла успешно
            if ((bool)$notification['paid'] === true) { // Если оплата успшена
                $metadata = $notification['metadata'];

                if (isset($metadata['transaction_id'])) { // Если есть transaction_id

                    // Общая информация о транзакции
                    $transactionId = (int)$metadata['transaction_id'];
                    // -----------------------------------------------------------------

                    // Участник оплатил участие в сборнике -------------------------------------------------------------------------------------------------
                    if ((int)($metadata['participation_id'] ?? null) > 0 && !($metadata['print_id'] ?? null)) { // Это оплата за сборник

                        $Participation = Participation::where('id', (int)$metadata['participation_id'])->first();
                        $Collection = Collection::where('id', $Participation['collection_id'])->first();
                        $user = User::where('id', $Participation['user_id'])->first();
                        $transaction = Transaction::where('id', $transactionId)->first();


                        if ($transaction['status'] !== PaymentStatusEnum::CONFIRMED) { // Еще не подтверждали эту транзакцию

                            // Записываем время оплаты на строку участия
                            Participation::where('id', (int)$metadata['participation_id'])
                                ->update(array(
                                    'paid_at' => Carbon::now('Europe/Moscow')->toDateTime(),
                                    'pat_status_id' => 3
                                ));

                            // Создаем награду юзеру
                            award::create([
                                'user_id' => $user['id'],
                                'award_type_id' => 4,
                                'collection_id' => $Collection['id']
                            ]);

                            // Посылаем Email уведомление пользователю
                            $user->notify(new EmailNotification(
                                'Оплата подтверждена!',
                                $user['name'],
                                "Отлично, вы успешно оплатили заявку в сборике: '" . $Collection['title'] .
                                "'. Следующий этап (предварительная проверка сборника) будет доступен " . Date::parse($Collection['col_date2'])->format('j F') . "! " .
                                "Вся подробная информация об издании сборника и вашем процессе указана на странице участия:",
                                "Ваша страница участия",
                                $metadata['url_redirect']));

                            $title = '💸 *Новая оплата по сборинку!* 💸';
                            $text = '*Автор:* ' . $Participation['name'] . " " . $Participation['surname'] .
                                "\n" . "*Сборник:* " . $Collection['title'] .
                                "\n" . "*Сумма:* " . $notification['amount']['value'] . " руб.";

                            // Посылаем Telegram уведомление нам
                            Notification::route('telegram', '-506622812')
                                ->notify(new TelegramNotification($title, $text,
                                    "Его страница участия",
                                    route('user_participation', $Participation['id'])));

                        }
                    }
                    // --------------------------------------------------------------------------------------------------------------------------------

                    // Автор оплатил все кроме печати -------------------------------------------------------------------------------------------------
                    if ((int)($metadata['own_book_id'] ?? null) > 0 && (string)($metadata['own_book_payment_type'] ?? null) == 'Without_Print') { // Это оплата за книгу (БЕЗ ПЕЧАТИ)
                        $own_book = own_book::where('id', (int)$metadata['own_book_id'])->first();
                        $user = User::where('id', $own_book['user_id'])->first();

                        if (($own_book['paid_at_without_print'] ?? null) === null) {  // Это НОВАЯ оплата
                            // Записываем время оплаты на строку участия
                            own_book::where('id', (int)$metadata['own_book_id'])
                                ->update(array(
                                    'paid_at_without_print' => Carbon::now('Europe/Moscow')->toDateTime(),
                                    'own_book_status_id' => 3,
                                    'inside_deadline' => Carbon::now()->addDays(11)->toDate(),
                                    'cover_deadline' => Carbon::now()->addDays(11)->toDate(),
                                ));


                            // Посылаем Email уведомление пользователю
                            $user->notify(new EmailNotification(
                                'Оплата подтверждена!',
                                $user['name'],
                                "Отлично, вы успешно оплатили работу с макетами по книге: \"" . $own_book['title'] .
                                "\". Следующие шаги Вы всегда сможете отсеживать на странице издания:",
                                "Страница издания",
                                $metadata['url_redirect']));

                            // Посылаем Telegram уведомление нам
                            Notification::route('telegram', '-506622812')
                                ->notify(new TelegramNotification('💸 Новая оплата по книге! 💸',
                                    '*Книга*: ' . $own_book['author'] . ": \"" . $own_book['title'] . "\"" .
                                    "\n" . "*Сумма:* " . ($own_book['total_price'] - $own_book['print_price']) . " руб. (печать у него на " . $own_book['print_price'] . " руб.)",
                                    "Его страница издания",
                                    route('own_books_page', $own_book['id'])));
                        }
                    }
                    // --------------------------------------------------------------------------------------------------------------------------------

                    // Автор оплатил печать книги -------------------------------------------------------------------------------------------------
                    if ((int)($metadata['own_book_id'] ?? null) > 0 && (string)($metadata['own_book_payment_type'] ?? null) == 'Print_only') { // Это оплата за печать книги


                        $own_book = own_book::where('id', (int)$metadata['own_book_id'])->first();
                        $user = User::where('id', $own_book['user_id'])->first();

                        if ($own_book['paid_at_print_only'] === null) {  // Это НОВАЯ оплата
                            // Записываем время оплаты на строку участия
                            own_book::where('id', (int)$metadata['own_book_id'])
                                ->update(array(
                                    'paid_at_print_only' => Carbon::now('Europe/Moscow')->toDateTime(),
                                    'own_book_status_id' => 5
                                ));

                            // Посылаем Email уведомление пользователю
                            $user->notify(new EmailNotification(
                                'Оплата подтверждена!',
                                $user['name'],
                                "Отлично, вы успешно оплатили печать книги: \"" . $own_book['title'] .
                                "\". Следующие шаги Вы всегда сможете отсеживать на странице издания:",
                                "Страница издания",
                                $metadata['url_redirect']));

                            // Посылаем Telegram уведомление нам
                            Notification::route('telegram', '-506622812')
                                ->notify(new TelegramNotification('💸 Новая оплата по печати книги! 💸',
                                    '*Книга:* ' . $own_book['author'] . ": \"" . $own_book['title'] . "\"" .
                                    "\n" . "*Сумма:* " . ($own_book['print_price']) . " руб.",
                                    "Его страница издания",
                                    route('own_books_page', $own_book['id'])));
                        }
                    }
                    // --------------------------------------------------------------------------------------------------------------------------------

                    // Это оплата за пересылку -------------------------------------------------------------------------------------------------
                    if ((int)($metadata['print_id'] ?? 0) > 0) { // Это оплата за пересылку

                        $print_order = Printorder::where('id', (int)$metadata['print_id'])->first();

                        $user = User::where('id', $print_order['user_id'])->first();
                        if ($print_order['paid_at'] === null) {  // Это НОВАЯ оплата
                            // Записываем время оплаты на строку отправления
                            Printorder::where('id', (int)$metadata['print_id'])
                                ->update(array(
                                    'paid_at' => Carbon::now('Europe/Moscow')->toDateTime(),
                                ));

                            // Посылаем Email уведомление пользователю
                            $user->notify(new EmailNotification(
                                'Оплата подтверждена!',
                                $user['name'],
                                "Отлично, вы успешно оплатили стоимость пересылки заказанных печатных материалов!" .
                                "'. Следующие шаги Вы всегда сможете отсеживать на странице издания:",
                                "Страница издания",
                                $metadata['url_redirect']));

                            // Посылаем Telegram уведомление нам
                            Notification::route('telegram', '-506622812')
                                ->notify(new TelegramNotification('💸 Новая оплата по пересылке! 💸',
                                    "*Сумма:* " . ($print_order['send_price']) . " руб.",
                                    "В админку",
                                    route('homeAdmin')));
                        }
                    }
                    // --------------------------------------------------------------------------------------------------------------------------------

                    // Клиент купил электронный сбрник -------------------------------------------------------------------------------------------------
                    if ((int)($metadata['bought_collection_id'] ?? 0) > 0) { // Это покупка электронного варианта

                        $digital_sale = digital_sale::where('user_id', $metadata['user_id'])
                            ->where('bought_collection_id', $metadata['bought_collection_id'])
                            ->value('bought_collection_id') ?? 0;


                        $collection = Collection::where('id', $metadata['bought_collection_id'])->first();
                        $user = User::where('id', $metadata['user_id'])->first();

                        if ($digital_sale === 0) { // Это НОВАЯ оплата за сборник

                            // Записываем данные электронной покупки
                            $new_digital_sale = new digital_sale();
                            $new_digital_sale->user_id = $metadata['user_id'];
                            $new_digital_sale->price = 100;
                            $new_digital_sale->bought_collection_id = $metadata['bought_collection_id'];
                            $new_digital_sale->save();

                            // Посылаем Email уведомление пользователю
                            $user->notify(new EmailNotification(
                                'Ваш электронный вариант готов!',
                                $user['name'],
                                "Отлично, вы успешно оплатили электронную версию сброрника: '" . $collection['title'] .
                                "'. Он всегда будет храниться в Вашем личном кабинете:",
                                "Купленные книги",
                                $metadata['url_redirect']));

                            // Посылаем Telegram уведомление нам
                            Notification::route('telegram', '-506622812')
                                ->notify(new TelegramNotification('💸 Новая покупка сборника! 💸', "Сборник: " . $collection['title'] .
                                    "\n" . "Сумма: 100 руб.",
                                    "Статистика покупок",
                                    route('user_participation', 232)));

                        }
                    }
                    // --------------------------------------------------------------------------------------------------------------------------------


                    // Клиент купил собственную книгу -------------------------------------------------------------------------------------------------
                    if ((int)($metadata['bought_own_book_id'] ?? 0) > 0) { // Это покупка электронного варианта

                        $digital_sale = digital_sale::where('user_id', $metadata['user_id'])
                            ->where('bought_own_book_id', $metadata['bought_own_book_id'])
                            ->value('bought_own_book_id') ?? 0;


                        $own_book = own_book::where('id', $metadata['bought_own_book_id'])->first();
                        $author = User::where('id', $own_book['user_id'])->first();
                        $user = User::where('id', $metadata['user_id'])->first();

                        if ($digital_sale === 0) { // Это НОВАЯ оплата за сборник

                            // Записываем данные электронной покупки
                            $new_digital_sale = new digital_sale();
                            $new_digital_sale->user_id = $metadata['user_id'];
                            $new_digital_sale->price = 100;
                            $new_digital_sale->bought_own_book_id = $metadata['bought_own_book_id'];
                            $new_digital_sale->save();

                            // Посылаем Email уведомление автору книги
                            $author->notify(new EmailNotification(
                                'Вашу книгу купили!',
                                $author['name'],
                                "Поздравляем! Кто-то только что купил вашу книгу: '" . $own_book['title'] .
                                "'. Информация о том, как вывести средства всегда будет указана на странице издания:",
                                "Страница издания",
                                route('book_page', $own_book['id'])));

                            // Посылаем Email уведомление покупателю
                            $user->notify(new EmailNotification(
                                'Ваш электронный вариант готов!',
                                $user['name'],
                                "Отлично, вы успешно оплатили электронную версию книги: '" . $own_book['title'] .
                                "'. Она всегда будет храниться в Вашем личном кабинете:",
                                "Купленные книги",
                                $metadata['url_redirect']));

                            // Посылаем Telegram уведомление нам
                            Notification::route('telegram', '-506622812')
                                ->notify(new TelegramNotification('💸 Новая покупка книги! 💸', "Книга: " . $own_book['title'] .
                                    "\n" . "Сумма: 100 руб.",
                                    "Статистика покупок",
                                    route('user_participation', 232)));

                        }
                    }
                    // --------------------------------------------------------------------------------------------------------------------------------


                    // Клиент пополнил себе кошелек -------------------------------------------------------------------------------------------------
                    if (($metadata['description'] ?? null) == 'Пополнение кошелька' && Transaction::where('id', $transactionId)->value('status') === 'CREATED') { // Это пополнение кошелька
                        $user = User::where('id', $metadata['user_id'])->first();
                        $old_amount = UserWallet::where('user_id', $user['id'])->value('cur_amount');
                        $new_amount = $old_amount + $metadata['amount'];


                        // Меняем баланс кошелька
                        UserWallet::where('user_id', $user['id'])
                            ->update(array(
                                'cur_amount' => $new_amount
                            ));
                        // -----------------------------------------------------------------

                        // Посылаем Email уведомление автору
                        $user->notify(new EmailNotification(
                            'Вашу баланс успешно пополнен!',
                            $user['name'],
                            "Поздравляем! Платеж на пополнение баланса прошел успешно.",
                            "Личный кабинет",
                            $metadata['url_redirect']));

                        // Посылаем Telegram уведомление нам
                        Notification::route('telegram', '-506622812')
                            ->notify(new TelegramNotification('💸 Новое зачисление в кабинет! 💸', "Юзер: " . $user['name'] . ' ' . $user['surname'] .
                                "\n" . "Сумма: " . $metadata['amount'] . " руб.",
                                "В админку",
                                route('homeAdmin')));

                    }
                    // --------------------------------------------------------------------------------------------------------------------------------

                    // Клиент оплачивает продвижение -------------------------------------------------------------------------------------------------
                    if (($metadata['description'] ?? null) == 'Оплата продвижения' && Transaction::where('id', $transactionId)->value('status') === 'CREATED') { // Это пополнение кошелька
                        $user = User::where('id', $metadata['user_id'])->first();
                        $transaction = Transaction::where('id', $transactionId)->first();

                        // Меняем статус продвижение на "Оплачено"
                        ext_promotion::where('id', $transaction['ext_promotion_id'])
                            ->update(array(
                                'ext_promotion_status_id' => 3,
                                'paid_at' => Carbon::now('Europe/Moscow')->toDateTime()
                            ));
                        // -----------------------------------------------------------------

                        // Посылаем Email уведомление автору
                        $user->notify(new EmailNotification(
                            'Ваше продвижение успешно оплачено!',
                            $user['name'],
                            "Поздравляем! Платеж на продвижение прошел успешно.",
                            "Личный кабинет",
                            $metadata['url_redirect']));

                        // Посылаем Telegram уведомление нам
                        Notification::route('telegram', '-4120321987')
                            ->notify(new TelegramNotification('💸 *Новая оплата на продвижение!* 💸', "*Автор:* " . $user['name'] . ' ' . $user['surname'] .
                                "\n" . "*Сумма:* " . $metadata['amount'] . " руб.",
                                null,
                                null));

                    }
                    // --------------------------------------------------------------------------------------------------------------------------------

                    // Общая информация о транзакции
                    // Меняем статус имеющейся транзакции
                    Transaction::where('id', $transactionId)
                        ->update(array(
                            'status' => PaymentStatusEnum::CONFIRMED,
                            'payment_method' => $notification['payment_method']['type'],
                        ));
                    // -----------------------------------------------------------------
                    (new DangerTasksService())->update($manual_update = true);
                }
            }
        }
    }
}
