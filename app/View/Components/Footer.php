<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;
use Illuminate\View\Component;

class Footer extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        if (Str::contains(request()->url(), 'social')) {
            $bg = 'bg-blue-500';
        } else {
            $bg = 'bg-green-500';
        }
        $manSitting = '/fixed/mascots/' . (config('app.winter_mode') ? 'man_sitting_winter.svg' : 'man_sitting.svg');
        return view('components.footer', ['bg' => $bg, 'manSitting' => $manSitting]);
    }
}
