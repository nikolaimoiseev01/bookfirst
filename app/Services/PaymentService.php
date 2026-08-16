<?php

namespace App\Services;

use App\Enums\ParticipationStatusEnums;
use App\Enums\TransactionPaymentProviderEnums;
use App\Enums\TransactionStatusEnums;
use App\Enums\TransactionTypeEnums;
use App\DTO\PaymentCallbackDto;
use App\Models\award;
use App\Models\Collection\Participation;
use App\Models\Transaction;
use App\Services\PaymentCallbackServices\CollectionPaymentService;
use App\Services\PaymentCallbackServices\ExtPromotionPaymentService;
use App\Services\PaymentCallbackServices\OwnBookPaymentService;
use App\Services\PaymentCallbackServices\ParticipationPaymentService;
use App\Services\PaymentCallbackServices\PurchasePrintPaymentService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
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

    public function createForeignPayment(
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
                'payment_provider' => TransactionPaymentProviderEnums::ROBOKASSA,
            ], $transactionData));

            $merchantLogin = config('services.robokassa.merchant_login');
            $password1 = config('services.robokassa.password1');
            $isTest = config('services.robokassa.is_test');

            $outSum = number_format($amount, 2, '.', '');
            $invId = $transaction->id;

            // В подписи SuccessUrl2 участвует в url-encoded виде
            $successUrl = urlencode($urlRedirect);
            $successUrlMethod = 'GET';

            $invId = 1000;

            $successUrl = "https://pervajakniga.ru/account/participations/2022?confirm_payment=collection_participation";

            // MerchantLogin:OutSum:InvId:SuccessUrl2:SuccessUrl2Method:Пароль#1
            $signature = md5("{$merchantLogin}:{$outSum}:{$invId}:{$successUrl}:{$successUrlMethod}:{$password1}");

            $params = [
                'MerchantLogin' => $merchantLogin,
                'OutSum' => $outSum,
                'InvId' => $invId,
                'SuccessUrl2' => $successUrl,
                'SuccessUrl2Method' => $successUrlMethod,
                'SignatureValue' => $signature,
            ];

            dd($params, $password1);

            if ($isTest) {
                $params['IsTest'] = 1;
            }

            $response = Http::asForm()
                ->post('https://auth.robokassa.ru/Merchant/Indexjson.aspx', $params);

            dd($response->json());

            $invoiceId = $response->json('invoiceID');

            if (empty($invoiceId)) {
                Log::error('Robokassa: не удалось получить ссылку на оплату', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
                throw new \RuntimeException('Не удалось создать ссылку на оплату Robokassa');
            }

            return "https://auth.robokassa.ru/Merchant/Index/{$invoiceId}";
        });
    }

    public function callbackPayment(PaymentCallbackDto $paymentDto): void
    {
        if ($paymentDto->transactionType == TransactionTypeEnums::COLLECTION_PARTICIPATION->value) {
            (new ParticipationPaymentService($paymentDto))->update();
        }
        if ($paymentDto->transactionType == TransactionTypeEnums::OWN_BOOK_WO_PRINT->value) {
            (new OwnBookPaymentService($paymentDto))->firstPayment();
        }
        if ($paymentDto->transactionType == TransactionTypeEnums::OWN_BOOK_PRINT->value) {
            (new OwnBookPaymentService($paymentDto))->firstAuthorPrintPayment();
        }
        if ($paymentDto->transactionType == TransactionTypeEnums::EXT_PROMOTION_PAYMENT->value) {
            (new ExtPromotionPaymentService($paymentDto))->update();
        }
        if ($paymentDto->transactionType == TransactionTypeEnums::COLLECTION_EBOOK_PURCHASE->value) {
            (new CollectionPaymentService($paymentDto))->ebookPuchase();
        }
        if ($paymentDto->transactionType == TransactionTypeEnums::OWN_BOOK_EBOOK_PURCHASE->value) {
            (new OwnBookPaymentService($paymentDto))->ebookPuchase();
        }
        if (in_array($paymentDto->transactionType, [
            TransactionTypeEnums::OWN_BOOK_ONLY->value,
            TransactionTypeEnums::COLLECTION_ONLY->value
        ])) {
            (new PurchasePrintPaymentService($paymentDto))->update();
        }
    }
}
