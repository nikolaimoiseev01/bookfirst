<?php

namespace App\View\Components\ProcessBlocks\PurchasePrint;

use App\Enums\PrintOrderStatusEnums;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Tracking extends Component
{
    /**
     * Create a new component instance.
     */
    public $printOrder;
    public $blockColor;

    public function __construct($printOrder)
    {
        $this->printOrder = $printOrder;
        $this->blockColor = match ($this->printOrder['status']) {
            PrintOrderStatusEnums::SENT => $this->blockColor = 'green',
            default => 'gray'
        };
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.process-blocks.purchase-print.tracking');
    }
}
