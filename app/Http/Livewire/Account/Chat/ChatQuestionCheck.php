<?php

namespace App\Http\Livewire\Account\Chat;


use Livewire\Component;

class ChatQuestionCheck extends Component
{

    public $chat;

    public function render()
    {
        return view('livewire.account.chat.chat-question-check');
    }

    public function mount($chat_id)
    {
        $this->chat = \App\Models\Chat::where('id', $chat_id)->first();

    }

    public function hide_message()
    {

        \App\Models\Chat::where('id', $this->chat['id'])->update([
            'flg_chat_read' => 1,
        ]);

        $this->dispatchBrowserEvent('hide_chat_notification', ['chat_id' => $this->chat['id']]);
    }
}
