<?php

namespace App\Livewire\Pages\Account\Chat;

use App\Models\Chat\Chat;
use App\Models\User\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Url;
use Livewire\Component;

class ChatsPage extends Component
{
    public $allChats;
    public $tab = 'support';
    public $chat;

    #[Url]
    public $cur_chat_id;
    public $cur_chat;
    public $user_avatar;

    public function render()
    {
        return view('livewire.pages.account.chat.chats-page')->layout('layouts.account');
    }

    public function mount()
    {
        $this->allChats = Chat::where('user_created', Auth::id())
            ->orWhere('user_to', Auth::id())
            ->with('messages')
            ->withMax('messages', 'created_at') // получаем поле messages_max_created_at
            ->orderBy('messages_max_created_at', 'desc')
            ->get();
        if (!$this->cur_chat_id) {
            $this->cur_chat_id = Chat::where('id', $this->allChats[0]['id'])->first()['id'];
        }
        $this->makeCurChat();
    }

    function makeCurChat()
    {
        $this->cur_chat = Chat::where('id', $this->cur_chat_id)->first();
        if ($this->cur_chat['flg_admin_chat']) {
            $this->user_avatar = '/fixed/avatar_admin.svg';
        } elseif ($this->chat['user_created'] != Auth::user()->id) {
            $this->user_avatar = getAvatarUrl(User::where('id', $this->chat['user_created'])->with('media')->first());
        } else {
            $this->user_avatar = getAvatarUrl(User::where('id', $this->chat['user_to'])->with('media')->first());
        }
    }

    public function changeChat($id)
    {
        $this->cur_chat_id = $id;
        $this->makeCurChat();
        $this->dispatch('refreshChat');
    }
}
