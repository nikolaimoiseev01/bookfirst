<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Book2d extends Component
{
    public $cover;
    /**
     * Create a new component instance.
     */
    public function __construct($cover)
    {
        $this->cover = $cover ?: '/fixed/cover_wip.png';
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.book2d');
    }
}
