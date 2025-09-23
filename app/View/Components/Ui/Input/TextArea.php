<?php

namespace App\View\Components\Ui\Input;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class TextArea extends Component
{
    public $model;
    public $attachable;
    public $description;
    public $sendable;
    public $multiple;

    /**
     * Create a new component instance.
     */
    public function __construct($model, $description=null, $sendable=true, $attachable=false, $multiple=true)
    {
        $this->model = $model;
        $this->attachable = $attachable;
        $this->description = $description;
        $this->sendable = $sendable;
        $this->multiple = $multiple;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.ui.input.text-area');
    }
}
