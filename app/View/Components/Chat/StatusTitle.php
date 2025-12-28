<?php

namespace App\View\Components\Chat;

use App\Enums\ChatStatusEnums;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class StatusTitle extends Component
{
    public $statuses;
    /**
     * Create a new component instance.
     */
    public function __construct(public $chat)
    {
        $this->statuses = ChatStatusEnums::cases();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.chat.status-title');
    }
}
