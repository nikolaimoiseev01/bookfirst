<?php

namespace App\View\Components\Ui\Input;

use App\Models\Chat\MessageTemplate;
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
    public $attachText;
    public $messageTemplatesShow;
    public $messageTemplates;

    /**
     * Create a new component instance.
     */
    public function __construct($textModel = 'text', $filesModel = 'files', $description = null, $sendable = true, $attachable = false, $messageTemplatesShow = false, $multiple = true, $fileTypes = [], $color = 'green-500', $attachText = null)
    {

        $this->textModel = $textModel;
        $this->filesModel = $filesModel;
        $this->attachable = $attachable;
        $this->messageTemplatesShow = $messageTemplatesShow;
        $this->description = $description;
        $this->sendable = $sendable;
        $this->multiple = $multiple;
        $this->color = $color;
        $this->attachText = $attachText ?? "Прикрепить файлы <br>(или перенесите файлы в поле текста)";
        if ($messageTemplatesShow) {
            $this->messageTemplates = MessageTemplate::query()
                ->orderBy('type')
                ->get(['type', 'title', 'text', 'id'])
                ->groupBy('type')
                ->toArray();
        }
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.ui.input.text-area');
    }
}
