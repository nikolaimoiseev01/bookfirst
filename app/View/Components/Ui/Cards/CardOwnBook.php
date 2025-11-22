<?php

namespace App\View\Components\Ui\Cards;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class CardOwnBook extends Component
{
    public $ownBook;
    /**
     * Create a new component instance.
     */
    public function __construct($ownbook)
    {
        $this->ownBook = $ownbook;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.ui.cards.card-own-book');
    }

    public function mount() {

    }
}
