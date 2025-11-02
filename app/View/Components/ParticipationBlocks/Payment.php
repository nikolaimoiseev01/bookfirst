<?php

namespace App\View\Components\ParticipationBlocks;

use App\Enums\ParticipationStatusEnums;
use App\Enums\TransactionStatusEnums;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Payment extends Component
{
    public $participation;
    public $printOrder;
    public $blockColor;

    /**
     * Create a new component instance.
     */
    public function __construct($part)
    {
        $this->participation = $part;
        $this->printOrder = $part->printOrder;
        match ($this->participation['status']) {
            ParticipationStatusEnums::APPROVE_NEEDED, ParticipationStatusEnums::NOT_ACTUAL => $this->blockColor = 'gray',
            ParticipationStatusEnums::PAYMENT_NEEDED => $this->blockColor = 'yellow',
            ParticipationStatusEnums::APPROVED => $this->blockColor = 'green'
        };
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        $paidAmount = $this->participation->transactions->where('status', TransactionStatusEnums::CONFIRMED)->sum('amount') ?? 00;
        $amountToPay = $this->participation['price_total'] + ($this->participation->printOrder['price_print'] ?? 0) - $paidAmount;
        return view('components.participation-blocks.payment',[
            'paidAmount' => $paidAmount,
            'amountToPay' => $amountToPay
        ]);
    }

}
