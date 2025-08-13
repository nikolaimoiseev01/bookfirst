<?php

namespace App\Livewire\Components\Account;

use Livewire\Component;
use Spatie\LivewireFilepond\WithFilePond;

class Chat extends Component
{
    use WithFilePond;

    public $chat;
    public $text;
    public $file;

    public function render()
    {
        return view('livewire.components.account.chat');
    }

    public function mount($chat) {
        $this->chat = $chat->load(['messages.user', 'chatStatus']);
    }

    public function send() {
        sleep(3);
//        dd($this->file);
    }
}
