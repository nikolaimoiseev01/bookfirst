<?php

namespace App\Livewire\Components\Portal;

use App\Services\PriceCalculation\CalculateOwnBookService;
use Livewire\Component;

class CalculatorOwnBook extends Component
{
    public $coverReady = false;
    public $needTextDesign = true;
    public $needTextCheck = false;
    public $needPrint = true;
    public $coverType = 'Мягкая';
    public $insideColor = 'Черно-белый';
    public $pages = 60;
    public $pagesColor = 0;
    public $booksCnt = 10;
    public $prices;
    public $needPromo;
    public $internalPromoType;

    public function render()
    {
        return view('livewire.components.portal.calculator-own-book');
    }

    public function mount() {
        $this->updatePrices();
    }

    public function updated($val): void
    {
        if ($val == 'needPromo') {
            $this->internalPromoType = $this->needPromo ? '1' : null;
        }
        $this->updatePrices();
    }

    public function updatePrices() {
        $this->prices = ((new CalculateOwnBookService(
            pages: $this->pages,
            pagesColor: $this->pagesColor,
            needTextDesign: $this->needTextDesign,
            needTextCheck: $this->needTextCheck,
            coverReady: $this->coverReady,
            needPrint: $this->needPrint,
            booksCnt: $this->booksCnt,
            coverType: $this->coverType,
            promoType: $this->internalPromoType,
        )
        )->calculate());
    }
}
