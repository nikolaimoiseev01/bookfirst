<?php

namespace App\View\Components\Input;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class SearchBar extends Component
{
    public $model;
    public $search_input;
    /**
     * Create a new component instance.
     */
    public function __construct($model, $input) {
        $this->model = $model;
        $this->search_input = $input;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.input.search-bar');
    }
}
