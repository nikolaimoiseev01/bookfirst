<?php

namespace App\View\Components\Input;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class RangeSlider extends Component
{

    public $model;

    /**
     * Create a new component instance.
     */
    public function __construct(string $model)
    {
        $this->model = $model;
        $this->model = $model;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.input.range-slider');
    }
}
