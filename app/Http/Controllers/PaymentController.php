<?php

namespace App\Http\Controllers;


use App\DTO\PaymentCallbackDto;
use App\Enums\TransactionStatusEnums;
use App\Enums\TransactionPaymentProviderEnums;
use App\Enums\TransactionTypeEnums;
use App\Models\Transaction;
use App\Services\InnerTasksService;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PaymentController
{
    public function callback()
    {
        $source = file_get_contents('php://input');
        $payload = json_decode($source, true);
        $yooKassaObject = $payload['object'] ?? null;
        $transactionId = data_get($yooKassaObject, 'metadata.transaction_id');
        if (!is_array($yooKassaObject) || !$transactionId) {
            return response('invalid notification', 400);
        }

        $transaction = Transaction::find($transactionId);
        if (!$transaction) {
            return response('transaction not found', 404);
        }

        if ($transaction->type === TransactionTypeEnums::AI_ATTEMPT_PACK_PURCHASE->value) {
            return $this->callbackAiAttemptPack($yooKassaObject, $transaction);
        }

        if ($yooKassaObject['status'] == 'succeeded' && $transaction['status'] != TransactionStatusEnums::CONFIRMED) {
            $paymentDto = PaymentCallbackDto::fromYooKassa($yooKassaObject);
            DB::transaction(function () use ($paymentDto) {
                (new PaymentService())->callbackPayment($paymentDto);
            });
            $transaction->update([
                'status' => TransactionStatusEnums::CONFIRMED,
                'payment_method' => $yooKassaObject['payment_method']['type'],
            ]);
        }
        (new InnerTasksService())->update();

        return response('OK');
    }

    private function callbackAiAttemptPack(array $notification, Transaction $transaction)
    {
        if ($transaction->status === TransactionStatusEnums::CONFIRMED->value) {
            return response('OK');
        }

        if ($transaction->payment_provider !== TransactionPaymentProviderEnums::YOOKASSA->value) {
            return response('invalid payment provider', 400);
        }

        if (($notification['status'] ?? null) !== 'succeeded') {
            return response('OK');
        }

        $paymentId = $notification['id'] ?? null;
        if (!$paymentId || $paymentId !== $transaction->yoo_id) {
            Log::warning('ИИ attempt payment callback has mismatched payment id', ['transaction_id' => $transaction->id]);
            return response('invalid payment', 400);
        }

        try {
            $payment = (new PaymentService())->getClient()->getPaymentInfo($paymentId);
        } catch (\Throwable $exception) {
            report($exception);
            return response('payment verification unavailable', 503);
        }

        if (!$payment || $payment->getId() !== $transaction->yoo_id || $payment->getStatus() !== 'succeeded') {
            return response('payment not confirmed', 400);
        }

        $metadata = $payment->getMetadata()?->toArray() ?? [];
        $amount = (float) ($payment->getAmount()?->getValue() ?? 0);
        $metadataData = json_decode((string) ($metadata['transaction_data'] ?? ''), true);
        $transactionData = $transaction->data ?? [];

        if ((int) ($metadata['transaction_id'] ?? 0) !== (int) $transaction->id
            || (int) ($metadata['user_id'] ?? 0) !== (int) $transaction->user_id
            || ($metadata['transaction_type'] ?? null) !== TransactionTypeEnums::AI_ATTEMPT_PACK_PURCHASE->value
            || number_format($amount, 2, '.', '') !== number_format((float) $transaction->amount, 2, '.', '')
            || (int) data_get($metadataData, 'attempts', 0) !== (int) data_get($transactionData, 'attempts', 0)) {
            Log::warning('ИИ attempt payment callback metadata does not match transaction', ['transaction_id' => $transaction->id]);
            return response('payment data mismatch', 400);
        }

        $paymentDto = new PaymentCallbackDto(
            transactionId: (int) $transaction->id,
            userId: (int) $transaction->user_id,
            transactionType: $transaction->type,
            transactionData: $transactionData,
            amount: $amount,
            paymentMethod: $payment->getPaymentMethod()?->getType(),
        );

        DB::transaction(function () use ($transaction, $paymentDto): void {
            $lockedTransaction = Transaction::query()->lockForUpdate()->findOrFail($transaction->id);
            if ($lockedTransaction->status === TransactionStatusEnums::CONFIRMED->value) {
                return;
            }

            (new PaymentService())->callbackPayment($paymentDto);
        });

        return response('OK');
    }

    public function robokassaCallback(Request $request)
    {
        Log::info('Robokassa callback', $request->all());
        $outSum = $request->input('OutSum');
        $invId = $request->input('InvId');
        $signatureValue = $request->input('SignatureValue');
        $password2 = config('services.robokassa.password2');

        // OutSum:InvId:Пароль#2
        $signature = md5("{$outSum}:{$invId}:{$password2}");

        if (!hash_equals(strtolower($signature), strtolower((string)$signatureValue))) {
            Log::error('Robokassa callback: неверная подпись', $request->all());
            return response('bad sign', 400);
        }

        $transaction = Transaction::where('id', $invId)->first();

        if (!$transaction) {
            Log::error('Robokassa callback: транзакция не найдена', $request->all());
            return response('transaction not found', 404);
        }

        if ($transaction['status'] != TransactionStatusEnums::CONFIRMED) {
            $paymentDto = PaymentCallbackDto::fromRobokassa(
                $transaction,
                (float)$outSum,
                $request->input('PaymentMethod')
            );

            DB::transaction(function () use ($paymentDto) {
                (new PaymentService())->callbackPayment($paymentDto);
            });

            $transaction->update([
                'status' => TransactionStatusEnums::CONFIRMED,
                'payment_method' => $paymentDto->paymentMethod,
            ]);
        }

        (new InnerTasksService())->update();

        return response("OK{$invId}");
    }
}
