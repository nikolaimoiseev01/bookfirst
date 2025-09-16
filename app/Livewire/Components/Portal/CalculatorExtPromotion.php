<?php

namespace App\Livewire\Components\Portal;

use Livewire\Component;

class CalculatorExtPromotion extends Component
{
    public $days = 1;
    public $site = 'stihi';
    public $hasPromo;
    public $options = [
        ['value'=>'stihi','label'=>'Стихи.ру'],
        ['value'=>'proza','label'=>'Проза.ру']
    ];

    public function render()
    {
        return view('livewire.components.portal.calculator-ext-promotion');
    }
}
