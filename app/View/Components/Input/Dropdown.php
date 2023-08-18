<?php

namespace App\View\Components\input;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Dropdown extends Component
{
    /**
     * Create a new component instance.
     */

    public $model;
    public $alltext;
    public $options;
    public $default;

    public function __construct($model, $options, $alltext, $default)
    {
        $this->model = $model;
        $this->options = $options;
        $this->alltext = $alltext == "null" ? null : $alltext;
        $this->default = $default == "null" ? null : $default;;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.input.dropdown');
    }
}
