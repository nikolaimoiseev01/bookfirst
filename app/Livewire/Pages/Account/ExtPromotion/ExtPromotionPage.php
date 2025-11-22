<?php

namespace App\Livewire\Pages\Account\ExtPromotion;

use App\Enums\TransactionTypeEnums;
use App\Models\ExtPromotion\ExtPromotion;
use App\Services\PaymentService;
use Livewire\Component;

class ExtPromotionPage extends Component
{
    public $extPromotion;
    public function render()
    {
        return view('livewire.pages.account.ext-promotion.ext-promotion-page')->layout('layouts.account');
    }

    public function mount($ext_promotion_id) {
        $this->extPromotion = ExtPromotion::where('id', $ext_promotion_id)->with('chat')->first();
    }

    public function createPayment($amount)
    {
        $paymentService = new PaymentService();
        $description = "Оплата продвижения на сайте '{$this->extPromotion['site']}' от автора {$this->extPromotion->user->getUserFullName()} (ext_promotion_id: {$this->extPromotion['id']})";
        $transactionData = [
            'type' => TransactionTypeEnums::EXT_PROMOTION_PAYMENT->value,
            'description' => $description,
            'model_type' => 'ExtPromotion',
            'model_id' => $this->extPromotion['id'],
            'data' => [
                'ext_promotion_id' => $this->extPromotion['id'],
            ]
        ];
        $urlRedirect = route('account.ext_promotion.index', $this->extPromotion['id'])  . '?confirm_payment=ext_promotion';
        $paymentUrl = $paymentService->createPayment(
            amount: $amount,
            urlRedirect: $urlRedirect,
            transactionData: $transactionData
        );
        $this->redirect($paymentUrl);
    }
}
