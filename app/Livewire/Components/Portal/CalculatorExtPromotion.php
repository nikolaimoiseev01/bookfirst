<?php

namespace App\Livewire\Components\Portal;

use App\Services\PriceCalculation\CalculateExtPromotionService;
use Livewire\Component;

class CalculatorExtPromotion extends Component
{
    public $days = 10;
    public $site = 'stihi';
    public $hasPromo = false;
    public $promocodeInput = 20;
    public $options = [
        ['value' => 'stihi', 'label' => 'Стихи.ру'],
        ['value' => 'proza', 'label' => 'Проза.ру']
    ];
    public $prices;

    public function render()
    {
        return view('livewire.components.portal.calculator-ext-promotion');
    }

    public function mount()
    {
        $this->updated();
    }

    public function updated()
    {
        $this->prices = ((new CalculateExtPromotionService(
            $this->site
            , $this->days
            , ($this->hasPromo ? $this->promocodeInput : 0)))
            ->calculate());
    }
}
