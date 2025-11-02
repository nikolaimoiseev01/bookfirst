<?php

namespace App\Services;

use App\Enums\ParticipationStatusEnums;
use App\Enums\TransactionStatusEnums;
use App\Enums\TransactionTypeEnums;
use App\Models\award;
use App\Models\Collection\Participation;
use App\Models\Transaction;
use App\Services\PaymentCallbackServices\ParticipationPaymentService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use YooKassa\Client;

class PaymentService
{
    public function getClient(): Client
    {
        $client = new Client();
        $client->setAuth(
            config('services.yookassa.shop_id'),
            config('services.yookassa.secret_key')
        );

        return $client;
    }

    /**
     * @throws \YooKassa\Common\Exceptions\ApiException
     * @throws \YooKassa\Common\Exceptions\BadApiRequestException
     * @throws \YooKassa\Common\Exceptions\ExtensionNotFoundException
     * @throws \YooKassa\Common\Exceptions\ForbiddenException
     * @throws \YooKassa\Common\Exceptions\InternalServerError
     * @throws \YooKassa\Common\Exceptions\NotFoundException
     * @throws \YooKassa\Common\Exceptions\ResponseProcessingException
     * @throws \YooKassa\Common\Exceptions\TooManyRequestsException
     * @throws \YooKassa\Common\Exceptions\UnauthorizedException
     */
    public function createPayment(
        float  $amount,
        string $urlRedirect,
        array  $transactionData = []
    ): string
    {
        return DB::transaction(function () use ($amount, $urlRedirect, $transactionData) {
            $transaction = Transaction::create(array_merge([
                'user_id' => Auth::id(),
                'status' => TransactionStatusEnums::CREATED,
                'amount' => $amount,
            ], $transactionData));

            $client = $this->getClient();

            $payment = $client->createPayment([
                'amount' => [
                    'value' => $amount,
                    'currency' => 'RUB',
                ],
                'confirmation' => [
                    'type' => 'redirect',
                    'return_url' => $urlRedirect,
                ],
                'metadata' => [
                    'user_id' => Auth::id(),
                    'transaction_id' => $transaction->id,
                    'transaction_type' => $transactionData['type'],
                    'transaction_data' => json_encode($transactionData['data']),
                ],
                'capture' => true,
                'description' => $transactionData['description'],
            ], uniqid('', true));

            $transaction->update([
                'yoo_id' => $payment->getId()
            ]);

            return $payment->getConfirmation()->getConfirmationUrl();
        });
    }

    public function callbackPayment($yooKassaObject): void
    {
        $metadata = $yooKassaObject['metadata'];

        if ($metadata['transaction_type'] == TransactionTypeEnums::COLLECTION_PARTICIPATION->value) {
            (new ParticipationPaymentService($yooKassaObject))->update();
        }
    }
}
