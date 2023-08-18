<?php

namespace App\View\Components\Social;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class WorkCard extends Component
{
    public $work;
    public $flgbigwork;

    /**
     * Create a new component instance.
     */
    public function __construct($work, $flgbigwork)
    {
        $this->work = $work;
        $this->flgbigwork = filter_var($flgbigwork, FILTER_VALIDATE_BOOLEAN);;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.social.work-card');
    }
}
