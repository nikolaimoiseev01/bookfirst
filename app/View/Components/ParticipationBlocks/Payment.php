<?php

namespace App\View\Components\ParticipationBlocks;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Payment extends Component
{
    public $participation;
    /**
     * Create a new component instance.
     */
    public function __construct($part)
    {
        $this->participation = $part;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.participation-blocks.payment');
    }
}
