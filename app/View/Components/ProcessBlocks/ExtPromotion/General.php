<?php

namespace App\View\Components\ProcessBlocks\ExtPromotion;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class General extends Component
{
    public $extPromotion;

    public function __construct($extPromotion)
    {
        $this->extPromotion = $extPromotion;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.process-blocks.ext-promotion.general');
    }
}
