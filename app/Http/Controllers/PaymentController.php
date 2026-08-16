<?php

namespace App\Http\Controllers;


use App\DTO\PaymentCallbackDto;
use App\Enums\TransactionStatusEnums;
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
        $yooKassaObject = json_decode($source, true)['object'];
        $transaction = Transaction::where('id', $yooKassaObject['metadata']['transaction_id'])->first();

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
