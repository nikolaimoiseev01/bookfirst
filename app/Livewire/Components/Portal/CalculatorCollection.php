<?php

namespace App\Livewire\Components\Portal;

use Livewire\Component;

class CalculatorCollection extends Component
{
    public $pages = 1;
    public $options = [
        ['value'=>1,'label'=>'1-7'],
        ['value'=>8,'label'=>'8-14'],
        ['value'=>15,'label'=>'15-21'],
        ['value'=>22,'label'=>'22-28'],
    ];

    public function render()
    {
        return view('livewire.components.portal.calculator-collection');
    }

    public function test() {
        dd($this->pages);
    }
}
