<?php

namespace App\View\Components\Chat;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\Component;

class Message extends Component
{
    public $message;
    public $role;
    /**
     * Create a new component instance.
     */
    public function __construct($message)
    {
        $this->message = $message;
        $this->role = $message->user->roles[0];
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.chat.message');
    }
}
