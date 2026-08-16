<?php

namespace App\Services\PaymentCallbackServices;


use App\DTO\PaymentCallbackDto;
use App\Enums\AwardTypeEnums;
use App\Enums\ParticipationStatusEnums;
use App\Enums\PrintOrderStatusEnums;
use App\Jobs\TelegramNotificationJob;
use App\Models\Award\Award;
use App\Models\Collection\Participation;
use App\Models\User\User;
use App\Notifications\Collection\PaymentParticipationSuccessNotification;
use App\Notifications\TelegramDefaultNotification;
use Illuminate\Support\Facades\Log;

class ParticipationPaymentService
{
    private PaymentCallbackDto $paymentDto;
    public function __construct(PaymentCallbackDto $paymentDto)
    {
        $this->paymentDto = $paymentDto;
    }

    public function update() {
        $transactionData = $this->paymentDto->transactionData;
        $participation = Participation::where('id', $transactionData['participation_id'])->first();
        $participation->update([
            'status' => ParticipationStatusEnums::APPROVED->value
        ]);
        if($participation->printOrder ?? null) {
            $participation->printOrder->update([
                'status' => PrintOrderStatusEnums::PAID
            ]);
        }
        Award::create([
            'user_id' => $participation['user_id'],
            'award_type_id' => AwardTypeEnums::COLLECTION_PARTICIPANT->id(),
            'model_type' => 'Collection',
            'model_id' => $participation['collection_id'],
        ]);
        $participation->promocodeStat?->update([
            'is_paid' => true
        ]);
        $amount = $this->paymentDto->amount;
        $user = User::where('id', $participation['user_id'])->first();
        $subject = '💸 *Новая оплата по сборинку!* 💸' . "\n\n";
        $notificationText =  $subject . '*Автор:* ' . $participation['author_name'] .
            "\n" . "*Сборник:* " . $participation->collection->title_short .
            "\n" . "*Сумма:* " . $amount . " руб.";

        $user->notify(new PaymentParticipationSuccessNotification($participation, $participation->collection, $this->paymentDto->amount));
        $notification = new TelegramDefaultNotification(null, $notificationText, null);
        TelegramNotificationJob::dispatch($notification);
    }
}
