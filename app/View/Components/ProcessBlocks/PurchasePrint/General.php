<?php

namespace App\View\Components\ProcessBlocks\PurchasePrint;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class General extends Component
{
    public $printOrder;
    /**
     * Create a new component instance.
     */
    public function __construct($printOrder)
    {
        $this->printOrder = $printOrder;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.process-blocks.purchase-print.general');
    }
}
