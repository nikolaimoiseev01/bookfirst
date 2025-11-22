<?php

namespace App\View\Components\ProcessBlocks\OwnBook;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class General extends Component
{
    /**
     * Create a new component instance.
     */
    public $ownBook;

    public function __construct($ownBook)
    {
        $this->ownBook = $ownBook;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.process-blocks.own-book.general');
    }
}
