<?php

namespace App\View\Components\ParticipationBlocks;

use App\Enums\CollectionStatusEnums;
use App\Enums\ParticipationStatusEnums;
use App\Models\Collection\CollectionVote;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Voting extends Component
{
    public $participation;
    public $collection;
    public $blockColor;

    /**
     * Create a new component instance.
     */
    protected $listeners = ['updateVoting' => '$refresh'];

    public function __construct($part)
    {
        $this->participation = $part;
        $this->collection = $part->collection;
        match ($this->collection['status']) {
            CollectionStatusEnums::APPS_IN_PROGRESS => $this->blockColor = 'gray',
            CollectionStatusEnums::PREVIEW => $this->blockColor = 'yellow',
            CollectionStatusEnums::PRINT_PREPARE, CollectionStatusEnums::PRINTING, CollectionStatusEnums::DONE => $this->blockColor = 'green'
        };
        $currentVote = CollectionVote::query()->where('participation_id_from', $this->participation['id'])->first();
        if ($currentVote) {
            $this->blockColor = 'green';
        }

        if ($this->participation['status'] != ParticipationStatusEnums::APPROVED) {
            $this->blockColor = 'gray';
        }
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.participation-blocks.voting');
    }
}
