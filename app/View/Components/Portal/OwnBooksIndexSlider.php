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
            ->where('internal_promo_type', 1)
            ->where('status_general', OwnBookStatusEnums::DONE)
            ->orderBy('created_at', 'desc')
            ->whereNot('internal_promo_type', 2)
            ->limit(10)
            ->get();
        $mainOwnBook = OwnBook::query()
            ->with(['media', 'user'])
            ->where('internal_promo_type', 2)
            ->where('status_general', OwnBookStatusEnums::DONE)
            ->inRandomOrder()
            ->first();
        return view('components.portal.own-books-index-slider', [
            'ownBooks' => $ownBooks,
            'mainOwnBook' => $mainOwnBook
        ]);
    }
}
