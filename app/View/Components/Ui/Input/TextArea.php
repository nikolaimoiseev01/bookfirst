<?php

namespace App\View\Components\Ui\Input;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class TextArea extends Component
{
    public $textModel;
    public $filesModel;
    public $attachable;
    public $fileTypes;
    public $description;
    public $sendable;
    public $multiple;
    public $color;
    public $isLivewire = true;

    /**
     * Create a new component instance.
     */
    public function __construct($textModel='text', $filesModel='files', $description=null, $sendable=true, $attachable=false, $multiple=true, $fileTypes=[], $color='green-500')
    {

        $this->textModel = $textModel;
        $this->filesModel = $filesModel;
        $this->attachable = $attachable;
        $this->description = $description;
        $this->sendable = $sendable;
        $this->multiple = $multiple;
        $this->color = $color;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.ui.input.text-area');
    }
}
