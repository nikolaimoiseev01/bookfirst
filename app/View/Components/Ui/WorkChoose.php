<?php

namespace App\View\Components\Ui;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class WorkChoose extends Component
{
    public $userWorks;
    public $disabled;
    /**
     * Create a new component instance.
     */
    public function __construct($userWorks, $disabled=false)
    {
        $this->userWorks = $userWorks;
        $this->disabled = $disabled;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.ui.work-choose');
    }
}
