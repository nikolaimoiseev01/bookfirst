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

    #[Url]
    public $curChatId;
    public $curChat;
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
        if (!$this->curChatId && count($this->allChats) > 0) {
            $this->curChatId = Chat::where('id', $this->allChats[0]['id'])->first()['id'];
        }
        if (count($this->allChats) > 0) {
            $this->makeCurChat();
        }

    }

    function makeCurChat()
    {
        $this->curChat = Chat::where('id', $this->curChatId)->first();
        $this->tab = $this->curChat['flg_admin_chat'] ? 'support' : 'personal';
        if ($this->curChat['flg_admin_chat']) {
            $this->user_avatar = '/fixed/avatar_admin.svg';
        } elseif ($this->curChat['user_created'] != Auth::user()->id) {
            $this->user_avatar = getUserAvatar(User::where('id', $this->curChat['user_created'])->with('media')->first());
        } else {
            $this->user_avatar = getUserAvatar(User::where('id', $this->curChat['user_to'])->with('media')->first());
        }
    }

    public function changeChat($id)
    {
        $this->curChatId = $id;
        $this->makeCurChat();
        $this->dispatch('refreshChat');
    }
}
