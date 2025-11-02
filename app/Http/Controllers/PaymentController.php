<?php

namespace App\Http\Controllers;


use App\Enums\TransactionStatusEnums;
use App\Models\Transaction;
use App\Services\PaymentService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PaymentController
{
    public function callback()
    {
        Log::info('YooKassa callback started!');

        $source = file_get_contents('php://input');
        $yooKassaObject = json_decode($source, true)['object'];
        $transaction = Transaction::where('id', $yooKassaObject['metadata']['transaction_id'])->first();

        if ($yooKassaObject['status'] == 'succeeded' && $transaction['status'] != TransactionStatusEnums::CONFIRMED) {
            DB::transaction(function () use ($yooKassaObject) {
                (new PaymentService())->callbackPayment($yooKassaObject);
            });
            $transaction->update([
                'status' => TransactionStatusEnums::CONFIRMED,
                'payment_method' => $yooKassaObject['payment_method']['type'],
            ]);
        }
    }
}
