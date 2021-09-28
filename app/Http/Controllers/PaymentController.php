<?php

namespace App\Http\Controllers;

use App\Enums\PaymentStatusEnum;
use App\Models\Transaction;
use App\Service\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use YooKassa\Model\Notification\NotificationSucceeded;
use YooKassa\Model\Notification\NotificationWaitingForCapture;
use YooKassa\Model\NotificationEventType;

class PaymentController extends Controller
{

    public function create(Request $request, PaymentService $service)
    {

        $amount = (float)$request->input('amount');
        $description = "Оплата участия";
        $url_redirect = url()->previous();

        // Записываем данные транзакции
        $transaction = new Transaction();
            $transaction->user_id = Auth::user()->id;
            $transaction->amount = $amount;
            $transaction->description = $description;
        $transaction->save();

        if ($transaction) {
            $link = $service->createPayment($amount, $description, $url_redirect, [
                'transaction_id' => $transaction->id
            ]);

            if (isset($link)) {
                return redirect()->away($link);
            }
        }

        Log::debug($transaction);

    }

    public function callback(Request $request, PaymentService $service)
    {
        Log::info('//////////////////////////  CALBACK STARTED //////////////////////////');


        $source = file_get_contents('php://input');
        $requestBody = json_decode($source, true);

        $notification = (isset($requestBody['event']) === NotificationEventType::PAYMENT_SUCCEEDED)
            ? new NotificationSucceeded($requestBody)
            : new NotificationWaitingForCapture($requestBody);

        $payment = $notification->getObject();


        $transaction = new Transaction();
        $transaction->description = $payment->status;
        $transaction->save();

        if(isset($payment->status) && $payment->status === 'waiting_for_capture') {
            $service->getClient()->capturePayment([
                'amount' => $payment->amount
            ], $payment->id, uniqid('', true));
        }

        if (isset($payment->status) && $payment->status === 'succeeded') {
            if ((bool)$payment->paid === true) {
                $metadata = (object)$payment->metadata;
                if (isset($metadata->transation_id)) {
                    $transactionId = (int)$metadata->transation_id;
                    $transaction = Transaction::find($transactionId);
                    $transaction->status = PaymentStatusEnum::CONFIRMED;
                    $transaction->save;

                    $payment->amount->value;
                }
            }
        }
        Log::info('//////////////////////////  CALBACK ENDED //////////////////////////');
    }
}
