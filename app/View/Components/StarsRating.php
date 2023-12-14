<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class StarsRating extends Component
{
    public $model;
    public $input_rating;
    /**
     * Create a new component instance.
     */
    public function __construct($model, $inputrating)
    {
        $this->model = $model;
        $this->input_rating = $inputrating;
    }
    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.stars-rating');
    }
}
