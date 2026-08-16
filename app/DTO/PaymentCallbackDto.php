<?php

namespace App\DTO;

use App\Models\Transaction;

class PaymentCallbackDto
{
    public function __construct(
        public int     $transactionId,
        public ?int    $userId,
        public string  $transactionType,
        public array   $transactionData,
        public float   $amount,
        public ?string $paymentMethod = null,
    ) {}

    public static function fromYooKassa(array $yooKassaObject): self
    {
        $metadata = $yooKassaObject['metadata'];

        return new self(
            transactionId: (int)$metadata['transaction_id'],
            userId: isset($metadata['user_id']) ? (int)$metadata['user_id'] : null,
            transactionType: $metadata['transaction_type'],
            transactionData: json_decode($metadata['transaction_data'], true) ?? [],
            amount: (float)$yooKassaObject['amount']['value'],
            paymentMethod: $yooKassaObject['payment_method']['type'] ?? null,
        );
    }

    public static function fromRobokassa(Transaction $transaction, float $outSum, ?string $paymentMethod = null): self
    {
        return new self(
            transactionId: (int)$transaction->id,
            userId: $transaction->user_id ? (int)$transaction->user_id : null,
            transactionType: $transaction->type,
            transactionData: $transaction->data ?? [],
            amount: $outSum,
            paymentMethod: $paymentMethod ?? 'robokassa',
        );
    }
}
