<?php

namespace App\View\Components\Ui\Cards;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class CardCollectionWide extends Component
{
    /**
     * Create a new component instance.
     */
    public $collection;

    public function __construct($collection)
    {
        $this->collection = $collection;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.ui.cards.card-collection-wide');
    }
}
