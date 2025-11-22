<?php

namespace App\View\Components\ProcessBlocks\OwnBook;

use App\Enums\OwnBookStatusEnums;
use App\Enums\ParticipationStatusEnums;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Payment extends Component
{
    /**
     * Create a new component instance.
     */
    public $ownBook;
    public $printOrder;
    public $blockColor;
    public function __construct($ownBook)
    {
        $this->ownBook = $ownBook;
        $this->printOrder = $ownBook->printOrder;
        match ($this->ownBook['status_general']) {
            OwnBookStatusEnums::REVIEW => $this->blockColor = 'gray',
            OwnBookStatusEnums::PAYMENT_REQUIRED => $this->blockColor = 'yellow',
            default => $this->blockColor = 'green',
        };
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.process-blocks.own-book.payment');
    }
}
