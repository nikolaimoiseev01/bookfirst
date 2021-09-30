<?php

namespace App\Http\Controllers;

use App\Enums\PaymentStatusEnum;
use App\Models\Collection;
use App\Models\own_book;
use App\Models\Participation;
use App\Models\Transaction;
use App\Models\User;
use App\Notifications\EmailNotification;
use App\Notifications\new_participation;
use App\Notifications\TelegramNotification;
use App\Service\PaymentService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use YooKassa\Model\Notification\NotificationSucceeded;
use YooKassa\Model\Notification\NotificationWaitingForCapture;
use YooKassa\Model\NotificationEventType;

class PaymentController extends Controller
{
    public $url_redirect;

    public function create_part_payment($participation_id, $amount, PaymentService $service)
    {

        $description = "Оплата участия в '" . Collection::where('id', Participation::where('id', $participation_id)->value('collection_id'))->value('title') . "'";
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
                'participation_id' => $participation_id,
                'own_book_id' => null,
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


        $description = "Оплата " . $own_book_payment_text . "книги '" . $own_book['title'] . "'";
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


    public function callback(Request $request, PaymentService $service)
    {

//        Log::info('//////////////////////////  CALBACK STARTED //////////////////////////');

        // Получите данные из POST-запроса от ЮKassa
        $source = file_get_contents('php://input');
        $requestBody = json_decode($source, true);
        $notification = $requestBody['object'];

        if (isset($notification['status']) && $notification['status'] === 'succeeded') { // Если операция прошла успешно
            if ((bool)$notification['paid'] === true) { // Если оплата успшена
                $metadata = $notification['metadata'];
                if (isset($metadata['transaction_id'])) { // Если есть transaction_id

                    // Общая информация о транзакции
                    $transactionId = (int)$metadata['transaction_id'];
                    // Меняем статус имеющейся транзакции
                    Transaction::where('id', $transactionId)
                        ->update(array('status' => PaymentStatusEnum::CONFIRMED));
                    // -----------------------------------------------------------------

                    // Участник оплатил сборник -------------------------------------------------------------------------------------------------
                    if ((int)$metadata['participation_id'] > 0) { // Это оплата за сборник

                        $Participation = Participation::where('id', (int)$metadata['participation_id'])->first();
                        $Collection = Collection::where('id', $Participation['collection_id'])->first();
                        $user = User::where('id', $Participation['user_id'])->first();

                        if ($Participation['paid_at'] === null) { // Это НОВАЯ оплата за сборник

                            // Записываем время оплаты на строку участия
                            Participation::where('id', (int)$metadata['participation_id'])
                                ->update(array(
                                    'paid_at' => Carbon::now('Europe/Moscow')->toDateTime(),
                                    'pat_status_id' => 3
                                ));

                            // Посылаем Email уведомление пользователю
                            $user->notify(new EmailNotification(
                                'Оплата подтверждена!',
                                $user['name'],
                                "Отлично, вы успешно оплатили заявку в сборике: '" . $Collection['title'] .
                                "'. Теперь остается ждать издания! Вся информацию по этому сборнику будет по ссылке:",
                                "Страница сборника",
                                $metadata['url_redirect']));

                            // Посылаем Telegram уведомление нам
                            Notification::route('telegram', '-506622812')
                                ->notify(new TelegramNotification('💸 Новая оплата по сборинку! 💸', 'Автор: ' . $Participation['name'] . " " . $Participation['surname'] .
                                    "\n" . "Сборник: " . $Collection['title'] .
                                    "\n" . "Сумма: " . $Participation['total_price'] . " руб.",
                                    "Его страница участия",
                                    route('user_participation', $Participation['id'])));

                        }
                    }
                    // --------------------------------------------------------------------------------------------------------------------------------


                    // Автор оплатил все кроме печати -------------------------------------------------------------------------------------------------
                    if ((int)$metadata['own_book_id'] > 0 && (string)$metadata['own_book_payment_type'] == 'Without_Print') { // Это оплата за книгу (БЕЗ ПЕЧАТИ)
                        $own_book = own_book::where('id', (int)$metadata['own_book_id'])->first();
                        $user = User::where('id', $own_book['user_id'])->first();

                        if ($own_book['paid_at_without_print'] === null) {  // Это НОВАЯ оплата
                            // Записываем время оплаты на строку участия
                            own_book::where('id', (int)$metadata['own_book_id'])
                                ->update(array(
                                    'paid_at_without_print' => Carbon::now('Europe/Moscow')->toDateTime(),
                                    'own_book_status_id' => 3
                                ));

                            // Посылаем Email уведомление пользователю
                            $user->notify(new EmailNotification(
                                'Оплата подтверждена!',
                                $user['name'],
                                "Отлично, вы успешно оплатили работу с макетами по книге: '" . $own_book['title'] .
                                "'. Следующие шаги Вы всегда сможете отсеживать на странице издания:",
                                "Страница издания",
                                $metadata['url_redirect']));

                            // Посылаем Telegram уведомление нам
                            Notification::route('telegram', '-506622812')
                                ->notify(new TelegramNotification('💸 Новая оплата по книге! 💸', 'Автор: ' . $own_book['author'] . "(юзер: " . $user['name'] . " " . $user['surname'] .
                                    "\n" . "Книга: " . $own_book['title'] .
                                    "\n" . "Сумма: " . ($own_book['total_price'] - $own_book['print_price']) . " руб. (печать у него на " . $own_book['print_price'] . " руб.",
                                    "Его страница издания",
                                    route('own_books_page', $own_book['id'])));
                        }
                    }
                    // --------------------------------------------------------------------------------------------------------------------------------


                    // Автор оплатил печать книги -------------------------------------------------------------------------------------------------
                    if ((int)$metadata['own_book_id'] > 0 && (string)$metadata['own_book_payment_type'] == 'Print_only') { // Это оплата за печать книги


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
                                "Отлично, вы успешно оплатили печать книги: '" . $own_book['title'] .
                                "'. Следующие шаги Вы всегда сможете отсеживать на странице издания:",
                                "Страница издания",
                                $metadata['url_redirect']));

                            // Посылаем Telegram уведомление нам
                            Notification::route('telegram', '-506622812')
                                ->notify(new TelegramNotification('💸 Новая оплата по печати книги! 💸', 'Автор: ' . $own_book['author'] . "(юзер: " . $user['name'] . " " . $user['surname'] .
                                    "\n" . "Книга: " . $own_book['title'] .
                                    "\n" . "Сумма: " . ($own_book['print_price']) . " руб.",
                                    "Его страница издания",
                                    route('own_books_page', $own_book['id'])));
                        }
                    }
                    // --------------------------------------------------------------------------------------------------------------------------------
                }
            }
        }

//        Log::info('//////////////////////////  CALBACK ENDED //////////////////////////');
    }
}
