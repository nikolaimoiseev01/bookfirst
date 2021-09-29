<?php

namespace App\Http\Controllers;

use App\Enums\PaymentStatusEnum;
use App\Models\Collection;
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
//
        // Получите данные из POST-запроса от ЮKassa
        $source = file_get_contents('php://input');
        $requestBody = json_decode($source, true);
        $notification = $requestBody['object'];

//        log::info($requestBody);

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
                                'Оплата подтвердена!',
                                $user['name'],
                                "Отлично, вы успешно оплатили заявку в сборике: '" . $Collection['title'] .
                                "'. Теперь остается ждать издания! Вся информацию по этому сборнику будет по ссылке:",
                                "Страница сборника",
                                $metadata['url_redirect']));

                            // Посылаем Telegram уведомление нам
                            Notification::route('telegram', '-506622812')
                                ->notify(new TelegramNotification('💸 Новая оплата! 💸', 'Автор: ' . $Participation['name'] . " " . $Participation['surname'] .
                                    "\n" . "Сборник: " . $Collection['title'] .
                                    "\n" . "Сумма: " . $Participation['total_price'] . " руб.",
                                    "Его страница участия",
                                    route('user_participation', $Participation['id'])));

                        }
                    }
                }
            }
        }
//
//        Log::info('//////////////////////////  CALBACK ENDED //////////////////////////');
    }
}
