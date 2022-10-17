<?php

namespace App\Http\Livewire;

use Livewire\Component;

class ChatQuestionCheck extends Component
{

    public $chat_id;

    public function render()
    {
        return view('livewire.chat-question-check');
    }

    public function hide_question() {

        \App\Models\Chat::where('id', $this->chat_id)->update([
            'flag_hide_question' => 1,
        ]);

        $this->dispatchBrowserEvent('hide_question_chat');
    }
}
