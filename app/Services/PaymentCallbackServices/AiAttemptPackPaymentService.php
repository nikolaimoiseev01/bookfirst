<?php

namespace App\Services\PaymentCallbackServices;

use App\DTO\PaymentCallbackDto;
use App\Enums\TransactionStatusEnums;
use App\Enums\TransactionPaymentProviderEnums;
use App\Enums\TransactionTypeEnums;
use App\Jobs\TelegramNotificationJob;
use App\Models\Transaction;
use App\Models\User\User;
use App\Notifications\TelegramDefaultNotification;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class AiAttemptPackPaymentService
{
    public function __construct(private readonly PaymentCallbackDto $paymentDto)
    {
    }

    public function update(): void
    {
        $notificationText = null;

        DB::transaction(function () use (&$notificationText): void {
            $transaction = Transaction::query()
                ->lockForUpdate()
                ->findOrFail($this->paymentDto->transactionId);

            if ($transaction->status === TransactionStatusEnums::CONFIRMED->value) {
                return;
            }

            if ($transaction->type !== TransactionTypeEnums::AI_ATTEMPT_PACK_PURCHASE->value
                || $transaction->payment_provider !== TransactionPaymentProviderEnums::YOOKASSA->value
                || $transaction->status !== TransactionStatusEnums::CREATED->value
                || (int) $transaction->user_id !== (int) $this->paymentDto->userId
                || number_format((float) $transaction->amount, 2, '.', '') !== number_format($this->paymentDto->amount, 2, '.', '')) {
                throw new RuntimeException('Данные платежа за попытки ИИ не совпадают с заказом.');
            }

            $attempts = (int) data_get($transaction->data, 'attempts', 0);
            if ($attempts < 1) {
                throw new RuntimeException('В заказе не указано количество попыток ИИ.');
            }

            $user = User::query()->lockForUpdate()->findOrFail($transaction->user_id);
            DB::table('users')->where('id', $user->id)->increment('ai_attempts_purchased', $attempts);

            $transaction->update([
                'status' => TransactionStatusEnums::CONFIRMED->value,
                'payment_method' => $this->paymentDto->paymentMethod,
            ]);

            $notificationText = "💸 *Покупка попыток ИИ* 💸\n\n"
                . '*Пользователь:* ' . $user->getUserFullName() . "\n"
                . '*Попыток:* ' . $attempts . "\n"
                . '*Сумма:* ' . $this->paymentDto->amount . ' руб.';
        });

        if ($notificationText !== null) {
            TelegramNotificationJob::dispatch(new TelegramDefaultNotification(null, $notificationText, null));
        }
    }
}
