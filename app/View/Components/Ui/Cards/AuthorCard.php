<?php

namespace App\View\Components\Ui\Cards;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class AuthorCard extends Component
{
    public $author;

    public function __construct($author)
    {
        $this->author = $author;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.ui.cards.author-card');
    }
}
