<?php

namespace App\Livewire\Pages\Account;

use App\Models\AlmostCompleteAction;
use Livewire\Component;

class ACAUnsubscribedPage extends Component
{
    public $unsubscribedText;

    public function render()
    {
        return view('livewire.pages.account.a-c-a-unsubscribed-page')->layout('layouts.account');
    }

    public function mount($aca_id) {
        $aca = AlmostCompleteAction::find($aca_id);
        $aca->update(['is_unsubscribed' => true]);
        $payload = $aca->type->payload($aca);
        $this->unsubscribedText = $payload['unsubscribe_text'];
    }
}
