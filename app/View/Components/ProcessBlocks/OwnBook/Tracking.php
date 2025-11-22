<?php

namespace App\View\Components\ProcessBlocks\OwnBook;

use App\Enums\OwnBookStatusEnums;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Tracking extends Component
{
    /**
     * Create a new component instance.
     */
    public $ownBook;
    public $blockColor;
    public function __construct($ownBook)
    {
        $this->ownBook = $ownBook;
        if ($this->ownBook["status_general"]->order() < OwnBookStatusEnums::PRINT_PAYMENT_REQUIRED->order()) {
            $this->blockColor = 'gray';
        } elseif ($this->ownBook["status_general"] == OwnBookStatusEnums::PRINT_PAYMENT_REQUIRED) {
            $this->blockColor = 'yellow';
        } elseif ($this->ownBook["status_general"]->order() < OwnBookStatusEnums::DONE->order()) {
            $this->blockColor = 'gray';
        } else {
            $this->blockColor = 'green';
        }
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.process-blocks.own-book.tracking');
    }
}
