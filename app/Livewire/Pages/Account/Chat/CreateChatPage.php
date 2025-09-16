<?php

namespace App\Livewire\Pages\Account\Chat;

use Livewire\Component;

class CreateChatPage extends Component
{
    public function render()
    {
        return view('livewire.pages.account.chat.create-chat-page')->layout('layouts.account');
    }

    public function createChat() {
        dd('DONE');
    }
}
