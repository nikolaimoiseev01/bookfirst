<?php

namespace App\View\Components\ProcessBlocks\OwnBook;

use App\Enums\OwnBookCoverStatusEnums;
use App\Enums\OwnBookInsideStatusEnums;
use App\Enums\OwnBookStatusEnums;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Preview extends Component
{
    /**
     * Create a new component instance.
     */
    public $ownBook;
    public $statusInside;
    public $statusCover;
    public $blockColor;

    public function __construct($ownBook)
    {
        $this->ownBook = $ownBook;

        if ($this->ownBook['status_general']->order() < 3) {
            $this->blockColor = 'gray';
        } elseif ($this->ownBook['status_general']->order() == 3) {
            if ($this->ownBook['status_inside']->order() < 4 || $this->ownBook['status_cover']->order() < 4) {
                $this->blockColor = 'yellow';
            } else {
                $this->blockColor = 'gray';
            }
        } else {
            $this->blockColor = 'green';
        }
        $this->statusInside = $this->ownBook['status_inside'];
        $this->statusCover = $this->ownBook['status_cover'];
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.process-blocks.own-book.preview');
    }
}
