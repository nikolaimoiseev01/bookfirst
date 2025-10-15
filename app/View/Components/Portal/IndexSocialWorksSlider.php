<?php

namespace App\View\Components\Portal;

use App\Models\Work\Work;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class IndexSocialWorksSlider extends Component
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
        $lastWorks = Work::query()
            ->with(['media', 'user'])
            ->get()
            ->take(5);
        return view('components.portal.index-social-works-slider', ['lastWorks' => $lastWorks]);
    }
}
