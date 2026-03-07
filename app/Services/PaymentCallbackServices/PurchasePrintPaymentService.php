<?php

namespace App\Services\PaymentCallbackServices;


use App\Enums\AwardTypeEnums;
use App\Enums\ExtPromotionStatusEnums;
use App\Enums\ParticipationStatusEnums;
use App\Enums\PrintOrderStatusEnums;
use App\Models\Award\Award;
use App\Models\Collection\Participation;
use App\Models\ExtPromotion\ExtPromotion;
use App\Models\PrintOrder\PrintOrder;
use App\Models\User\User;
use App\Notifications\Collection\PaymentParticipationSuccessNotification;
use App\Notifications\ExtPromotion\ExtPromotionPaymentSuccessNotification;
use App\Notifications\PurchasePrint\PurchasePrintPaymentSuccessNotification;
use App\Services\ExtPromotionStatUpdateService;
use Illuminate\Support\Facades\Log;

class PurchasePrintPaymentService
{
    private array $yooKassaObject;
    public function __construct(array $yooKassaObject)
    {
        $this->yooKassaObject = $yooKassaObject;
    }

    public function update() {

        $transactionData = json_decode($this->yooKassaObject['metadata']['transaction_data'], true);
        $printOrder = PrintOrder::where('id', $transactionData['print_order_id'])->first();
        $printOrder->update([
            'status' => PrintOrderStatusEnums::PAID->value
        ]);
        $user = User::where('id', $printOrder['user_id'])->first();
        $user->notify(new PurchasePrintPaymentSuccessNotification($printOrder, $this->yooKassaObject['amount']['value']));
    }
}
