<?php

namespace App\View\Components\ProcessBlocks\Participation;

use App\Enums\CollectionStatusEnums;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Tracking extends Component
{
    public $participation;
    public $collection;
    public $blockColor;
    /**
     * Create a new component instance.
     */
    public function __construct($part)
    {
        $this->participation = $part;
        $this->collection = $part->collection;
        $this->blockColor = 'gray';
        if ($this->collection['status'] == CollectionStatusEnums::PRINTING) {
            $this->blockColor = 'yellow';
        }
        if ($this->collection['status'] == CollectionStatusEnums::DONE || !($part->printOrder)) {
            $this->blockColor = 'green';
        }
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.process-blocks.participation.tracking');
    }
}
