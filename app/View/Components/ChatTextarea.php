<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ChatTextarea extends Component
{
    public $placeholder;
    public $attachable;
    public $sendable;
    public $model;
    /**
     * Create a new component instance.
     */
    public function __construct($attachable, $sendable, $placeholder, $model)
    {
        $this->attachable = filter_var($attachable, FILTER_VALIDATE_BOOLEAN);
        $this->sendable = filter_var($sendable, FILTER_VALIDATE_BOOLEAN);
        $this->placeholder = $placeholder;
        $this->model = $model;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
       return view('components.chat-textarea');
    }
}
