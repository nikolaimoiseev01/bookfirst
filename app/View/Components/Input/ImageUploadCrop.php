<?php

namespace App\View\Components\input;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ImageUploadCrop extends Component
{
    public $cropped;
    /**
     * Create a new component instance.
     */
    public function __construct($cropped)
    {
        $this->cropped = $cropped;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.input.image-upload-crop');
    }
}
