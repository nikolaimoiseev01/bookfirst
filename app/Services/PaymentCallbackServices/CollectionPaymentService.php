<?php

namespace App\Services\PaymentCallbackServices;


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
    private array $yooKassaObject;
    public function __construct(array $yooKassaObject)
    {
        $this->yooKassaObject = $yooKassaObject;
    }

    public function ebookPuchase() {

        $transactionData = json_decode($this->yooKassaObject['metadata']['transaction_data'], true);
        DigitalSale::create([
            'user_id' => $transactionData['user_id'],
            'model_type' => 'Collection',
            'model_id' => $transactionData['collection_id'],
            'price' => $this->yooKassaObject['amount']['value'],
        ]);
        TelegramNotificationJob::dispatch(new TelegramDefaultNotification("💸 Покупка электронного сборника 💸", "" ));
    }
}
