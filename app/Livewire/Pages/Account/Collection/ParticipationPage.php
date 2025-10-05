<?php

namespace App\Livewire\Pages\Account\Collection;

use App\Models\Collection\Participation;
use Livewire\Component;

class ParticipationPage extends Component
{
    public $participation;
    public $isSending;

    public function render()
    {
        return view('livewire.pages.account.collection.participation-page')->layout('layouts.account');
    }

    public function mount($participation_id)
    {
        $this->participation = Participation::where('id', $participation_id)
            ->with(['collection', 'chat', 'participationWorks', 'participationWorks.work', 'previewComments'])
            ->first();
    }
}
