<?php

namespace App\Livewire\Components\Portal;

use App\Services\PriceCalculation\CalculateParticipationService;
use Livewire\Component;

class CalculatorCollection extends Component
{
    public $pages = 1;
    public $options = [
        ['value' => 1, 'label' => '1-7'],
        ['value' => 8, 'label' => '8-14'],
        ['value' => 15, 'label' => '15-21'],
        ['value' => 22, 'label' => '22-28'],
    ];
    public $prices;
    public $needCheck = false;
    public $hasPromo = false;
    public $promocodeInput = 20;
    public $booksCnt = 1;


    public function render()
    {
        return view('livewire.components.portal.calculator-collection');
    }

    public function mount()
    {
        $this->updated();
    }

    public function updated(): void
    {
        $this->promocodeInput = min((int)$this->promocodeInput, 99);
        $this->prices = ((new CalculateParticipationService(
            $this->pages,
            true,
            $this->booksCnt,
            $this->needCheck,
            $this->hasPromo ? $this->promocodeInput : 0)
        )->calculate());
    }

}
