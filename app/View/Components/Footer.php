<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Footer extends Component
{
    public $mode;
    public $manvisible;
    /**
     * Create a new component instance.
     */
    public function __construct($manvisible, $mode)
    {

        $this->mode = $mode;
        $this->manvisible = filter_var($manvisible, FILTER_VALIDATE_BOOLEAN);

    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.footer');
    }
}
