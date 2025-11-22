<?php

namespace App\View\Components\ProcessBlocks\ExtPromotion;

use App\Enums\ExtPromotionStatusEnums;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Payment extends Component
{
    public $extPromotion;
    public $blockColor;

    public function __construct($extPromotion)
    {
        $this->extPromotion = $extPromotion;
        match ($this->extPromotion['status']) {
            ExtPromotionStatusEnums::REVIEW, ExtPromotionStatusEnums::NOT_ACTUAL => $this->blockColor = 'gray',
            ExtPromotionStatusEnums::PAYMENT_REQUIRED => $this->blockColor = 'yellow',
            ExtPromotionStatusEnums::IN_PROGRESS, ExtPromotionStatusEnums::DONE, ExtPromotionStatusEnums::START_REQUIRED => $this->blockColor = 'green'
        };
    }

    public function render(): View|Closure|string
    {

        return view('components.process-blocks.ext-promotion.payment');
    }

}
