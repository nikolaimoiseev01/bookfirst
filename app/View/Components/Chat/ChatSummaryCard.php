<?php

namespace App\View\Components\Chat;

use App\Models\User\User;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\Component;

class ChatSummaryCard extends Component
{
    public $chat;
    public $user_avatar;
    public $chosen;

    /**
     * Create a new component instance.
     */
    public function __construct($chat, $chosen)
    {
        $this->chat = $chat;

        if ($this->chat['flg_admin_chat']) {
            $this->user_avatar = '/fixed/avatar_admin.svg';
        } elseif ($this->chat['user_created'] != Auth::user()->id) {
            $this->user_avatar = getUserAvatar(User::where('id', $this->chat['user_created'])->with('media')->first());
        } else {
            $this->user_avatar = getUserAvatar(User::where('id', $this->chat['user_to'])->with('media')->first());
        }
        $this->chosen = $chosen;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.chat.chat-summary-card');
    }
}
