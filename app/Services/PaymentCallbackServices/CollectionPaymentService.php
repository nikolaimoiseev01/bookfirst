<?php

namespace App\Services\PaymentCallbackServices;


use App\DTO\PaymentCallbackDto;
use App\Enums\AwardTypeEnums;
use App\Enums\ExtPromotionStatusEnums;
use App\Enums\ParticipationStatusEnums;
use App\Jobs\TelegramNotificationJob;
use App\Models\Award\Award;
use App\Models\Collection\Participation;
use App\Models\DigitalSale;
use App\Models\ExtPromotion\ExtPromotion;
use App\Models\User\User;
use App\Notifications\Collection\PaymentParticipationSuccessNotification;
use App\Notifications\ExtPromotion\ExtPromotionPaymentSuccessNotification;
use App\Notifications\TelegramDefaultNotification;
use App\Services\ExtPromotionStatUpdateService;
use Illuminate\Support\Facades\Log;

class CollectionPaymentService
{
    private PaymentCallbackDto $paymentDto;
    public function __construct(PaymentCallbackDto $paymentDto)
    {
        $this->paymentDto = $paymentDto;
    }

    public function ebookPuchase() {

        $transactionData = $this->paymentDto->transactionData;
        DigitalSale::create([
            'user_id' => $transactionData['user_id'],
            'model_type' => 'Collection',
            'model_id' => $transactionData['collection_id'],
            'price' => $this->paymentDto->amount,
        ]);
        TelegramNotificationJob::dispatch(new TelegramDefaultNotification("💸 Покупка электронного сборника 💸", "" ));
    }
}
