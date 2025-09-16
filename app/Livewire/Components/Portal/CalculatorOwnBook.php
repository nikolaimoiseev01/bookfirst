<?php

namespace App\Livewire\Components\Portal;

use Livewire\Component;

class CalculatorOwnBook extends Component
{
    public $insideReady = false;
    public $coverReady = false;
    public $needTextDesign = false;
    public $needTextCheck = false;
    public $needPrint = false;
    public $coverType = 'Мягкая';
    public $insideColor = 'Черно-белый';
    public $colorPages = 0;
    public $booksCnt = 1;

    public function render()
    {
        return view('livewire.components.portal.calculator-own-book');
    }

    public function test()
    {
        dd($this->insideReady);
    }
}
