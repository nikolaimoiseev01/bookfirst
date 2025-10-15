<?php

namespace App\View\Components\Ui;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class CardSocialWork extends Component
{
    public $work;

    /**
     * Create a new component instance.
     */
    public function __construct($work)
    {
        $this->work = $work;
        if ($this->work->getFirstMediaUrl('cover') ?? null) {
            $work['cover_url'] = $this->work->getFirstMediaUrl('cover');
        } else {
            $rnd = Rand(1, 4);
            $work['cover_url'] = "/fixed/default_work_pic_{$rnd}.svg";
        }
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.ui.card-social-work');
    }
}
