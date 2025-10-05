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
        $this->participation = Participation::find($participation_id)->with('collection')->with('works.work')->first();
    }
}
