<?php

namespace App\View\Components\ParticipationBlocks;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Payment extends Component
{
    public $participation;
    public $blockColor;
    /**
     * Create a new component instance.
     */
    public function __construct($part)
    {
        $this->participation = $part;
        match ($this->participation['participation_status_id']) {
            1 => $this->blockColor = 'gray',
            2 => $this->blockColor = 'yellow',
            3 => $this->blockColor = 'green'
        };
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.participation-blocks.payment');
    }

    public function mount() {
    }
}
