<?php

namespace App\View\Components\ParticipationBlocks;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Template extends Component
{
    public $color;
    public $title;
    public $icon;
    public $shadow;

    /**
     * Create a new component instance.
     */
    public function __construct($status, $title)
    {
        $this->color = $status;
        $this->title = $title;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        if ($this->color == 'green') {
            $this->color = 'green-500';
            $this->icon = '/fixed/icons/check_green.svg';
            $this->shadow = '!shadow-[0_0_7px_1px_#47af9880]';
        } elseif ($this->color == 'dark') {
            $this->color = 'dark-300';
            $this->icon = '/fixed/icons/hourglass_grey.svg';
            $this->shadow = '';
        } elseif ($this->color == 'yellow') {
            $this->color = 'brown-400';
            $this->icon = '/fixed/icons/hourglass_yellow.svg';
            $this->shadow = '!shadow-[0_0_7px_1px_#FFA50080]';
        }

        return view('components.participation-blocks.template');
    }
}
