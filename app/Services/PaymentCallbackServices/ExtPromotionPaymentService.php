<?php

namespace App\Services\PaymentCallbackServices;


use App\Enums\AwardTypeEnums;
use App\Enums\ExtPromotionStatusEnums;
use App\Enums\ParticipationStatusEnums;
use App\Jobs\TelegramNotificationJob;
use App\Models\Award\Award;
use App\Models\Collection\Participation;
use App\Models\ExtPromotion\ExtPromotion;
use App\Models\User\User;
use App\Notifications\Collection\PaymentParticipationSuccessNotification;
use App\Notifications\ExtPromotion\ExtPromotionPaymentSuccessNotification;
use App\Notifications\TelegramDefaultNotification;
use App\Services\ExtPromotionStatUpdateService;
use Illuminate\Support\Facades\Log;
use NotificationChannels\Telegram\TelegramMessage;

class ExtPromotionPaymentService
{
    private array $yooKassaObject;
    public function __construct(array $yooKassaObject)
    {
        $this->yooKassaObject = $yooKassaObject;
    }

    public function update() {

        $transactionData = json_decode($this->yooKassaObject['metadata']['transaction_data'], true);
        $extPromotion = ExtPromotion::where('id', $transactionData['ext_promotion_id'])->first();
        $extPromotion->update([
            'status' => ExtPromotionStatusEnums::START_REQUIRED->value
        ]);
        (new ExtPromotionStatUpdateService($extPromotion))->addNewStat();
        $user = User::where('id', $extPromotion['user_id'])->first();
        $amount = $this->yooKassaObject['amount']['value'];

        $user->notify(new ExtPromotionPaymentSuccessNotification($extPromotion, $amount));

        $subject = '💸 *Новая оплата по продвижению!* 💸' . "\n\n";
        $notificationText = $subject . '*Автор:* ' . $extPromotion->user->getUserFullName() .
            "\n" . "*Сумма:* " . $amount . " руб.";
        $notification = new TelegramDefaultNotification(null, $notificationText, null, 'extPromotion');
        TelegramNotificationJob::dispatch($notification);
    }
}
