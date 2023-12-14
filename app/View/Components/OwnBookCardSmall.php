<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class OwnBookCardSmall extends Component
{
    public $own_book;
    /**
     * Create a new component instance.
     */
    public function __construct($ownbook)
    {
        $this->own_book = $ownbook;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.own-book-card-small');
    }
}
