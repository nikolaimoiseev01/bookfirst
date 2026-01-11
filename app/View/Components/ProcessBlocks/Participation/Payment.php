<?php

namespace App\View\Components\ProcessBlocks\Participation;

use App\Enums\CollectionStatusEnums;
use App\Enums\ParticipationStatusEnums;
use App\Enums\TransactionStatusEnums;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Payment extends Component
{
    public $participation;
    public $printOrder;
    public $collection;
    public $blockColor;
    public $paidAmount;

    /**
     * Create a new component instance.
     */
    public function __construct($part)
    {
        $this->participation = $part;
        $this->printOrder = $part->printOrder;
        $this->collection = $this->participation->collection;
        $this->paidAmount = $this->participation->transactions?->where('status', TransactionStatusEnums::CONFIRMED)->sum('amount');
        match ($this->participation['status']) {
            ParticipationStatusEnums::APPROVE_NEEDED, ParticipationStatusEnums::NOT_ACTUAL => $this->blockColor = 'gray',
            ParticipationStatusEnums::PAYMENT_REQUIRED => $this->blockColor = 'yellow',
            ParticipationStatusEnums::APPROVED => $this->blockColor = 'green'
        };
        if ($this->collection['status'] <> CollectionStatusEnums::APPS_IN_PROGRESS
            && $this->participation['status'] <> ParticipationStatusEnums::APPROVED) {
            $this->blockColor = 'gray';
        }
        if ($this->participation['status'] == ParticipationStatusEnums::PAYMENT_REQUIRED && $this->paidAmount > 0) {
            $this->blockColor = 'yellow';
        }
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        $paidAmount = $this->participation->transactions->where('status', TransactionStatusEnums::CONFIRMED)->sum('amount') ?? 00;
        $amountToPay = $this->participation['price_total'] + ($this->participation->printOrder['price_print'] ?? 0) - $paidAmount;
        return view('components.process-blocks.participation.payment',[
            'paidAmount' => $paidAmount,
            'amountToPay' => $amountToPay
        ]);
    }

}
