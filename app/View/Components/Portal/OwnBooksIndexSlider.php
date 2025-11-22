<?php

namespace App\View\Components\Portal;

use App\Enums\OwnBookStatusEnums;
use App\Models\OwnBook\OwnBook;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class OwnBooksIndexSlider extends Component
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
        $ownBooks = OwnBook::query()
            ->inRandomOrder(10)
            ->with('media')
            ->where('status_general', OwnBookStatusEnums::DONE)
            ->limit(10)
            ->get();
        return view('components.portal.own-books-index-slider', [
            'ownBooks' => $ownBooks
        ]);
    }
}
