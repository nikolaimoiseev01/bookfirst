<?php

namespace App\Service;

use YooKassa\Client;

class PaymentService
{
    public function getClient(): Client
    {
        $client = new Client();
        $client->setAuth(config('services.yookassa.shop_id'), config('services.yookassa.secret_key'));

        return $client;
    }

    /**
     * @param float $amount
     * @param string $description
     * @param array $options
     * @return string
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
    public function createPayment(float $amount, string $description, string $url_redirect, array $options = [])
    {

        $client = $this->getClient();
        $payment = $client->createPayment(
            array(
                'amount' => array(
                    'value' => $amount,
                    'currency' => 'RUB',
                ),
                'confirmation' => array(
                    'type' => 'redirect',
                    'return_url' => $url_redirect,
                ),
                'metadata' => [
                    'user_id' => $options['user_id'],
                    'transaction_id' => $options['transaction_id'],
                    'participation_id' => $options['participation_id'] ?? null,
                    'description' => $options['description'] ?? null,
                    'amount' => $options['amount'] ?? null,
                    'print_id' => $options['print_id'] ?? null,
                    'col_adit_print_needed' => $options['col_adit_print_needed'] ?? null,
                    'col_adit_print_type' => $options['col_adit_print_type'] ?? null,
                    'col_adit_send_to_name' => $options['col_adit_send_to_name'] ?? null,
                    'col_adit_send_to_tel' => $options['col_adit_send_to_tel'] ?? null,
                    'col_adit_send_to_address' => $options['col_adit_send_to_address'] ?? null,
                    'own_book_id' => $options['own_book_id'] ?? null,
                    'own_book_payment_type' => $options['own_book_payment_type'] ?? null,
                    'bought_collection_id' => $options['bought_collection_id'] ?? null,
                    'bought_own_book_id' => $options['bought_own_book_id'] ?? null,
                    'url_redirect' => $url_redirect,
                ],
                'capture' => true,
                'description' => $description,
            ),
            uniqid('', true));

        return $payment->getConfirmation()->getConfirmationUrl();

    }

}
