<?php

namespace App\Http\Livewire\Account\Chat;

use App\Models\Message;
use Livewire\Component;

class ChatQuestionCheck extends Component
{

    public $mes_id;

    public function render()
    {
        return view('livewire.account.chat.chat-question-check');
    }

    public function mount($mes_id)
    {

        $this->mes_id = $mes_id;

    }

    public function hide_message() {

        Message::where('id', $this->mes_id)->update([
            'flag_mes_read' => 1,
        ]);

        $this->dispatchBrowserEvent('hide_mes_notification', ['mes_id' => $this->mes_id]);
    }
}
