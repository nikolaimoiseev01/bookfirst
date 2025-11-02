<?php

namespace App\Livewire\Pages\Account\Collection;

use App\Models\Collection\Participation;
use Livewire\Component;

class ParticipationEditPage extends Component
{
    public $participation;

    public function render()
    {
        return view('livewire.pages.account.collection.participation-edit-page')->layout('layouts.account');
    }

    public function mount($participation_id)
    {
        $this->participation = Participation::where('id', $participation_id)->with(['collection', 'participationWorks.work'])->first();
    }
}
