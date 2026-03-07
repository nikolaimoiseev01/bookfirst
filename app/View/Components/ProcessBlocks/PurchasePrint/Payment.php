<?php

namespace App\View\Components\ProcessBlocks\PurchasePrint;

use App\Enums\PrintOrderStatusEnums;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Payment extends Component
{
    public $printOrder;
    public $blockColor;

    public function __construct($printOrder)
    {
        $this->printOrder = $printOrder;
        match ($this->printOrder['status']) {
            PrintOrderStatusEnums::CREATED, PrintOrderStatusEnums::NOT_ACTUAL => $this->blockColor = 'gray',
            PrintOrderStatusEnums::PAYMENT_REQUIRED => $this->blockColor = 'yellow',
            PrintOrderStatusEnums::PAID, PrintOrderStatusEnums::PRINTING, PrintOrderStatusEnums::SEND_NEED, PrintOrderStatusEnums::SENT => $this->blockColor = 'green'
        };
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.process-blocks.purchase-print.payment');
    }
}
