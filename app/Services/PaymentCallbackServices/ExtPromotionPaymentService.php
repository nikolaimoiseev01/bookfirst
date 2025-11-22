<?php

namespace App\Services\PaymentCallbackServices;


use App\Enums\AwardTypeEnums;
use App\Enums\ExtPromotionStatusEnums;
use App\Enums\ParticipationStatusEnums;
use App\Models\Award\Award;
use App\Models\Collection\Participation;
use App\Models\ExtPromotion\ExtPromotion;
use App\Models\User\User;
use App\Notifications\Collection\PaymentParticipationSuccessNotification;
use App\Notifications\ExtPromotion\ExtPromotionPaymentSuccessNotification;
use App\Services\ExtPromotionStatUpdateService;
use Illuminate\Support\Facades\Log;

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
        $user->notify(new ExtPromotionPaymentSuccessNotification($extPromotion, $this->yooKassaObject['amount']['value']));
    }
}
