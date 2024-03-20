<?php

namespace App\Http\Livewire\Portal;

use Livewire\Component;
use App\Service\ExtPromotionOutputsService;

class ExtPromotionCalc extends Component
{

    public $days = 5;
    public $site = 'stihi';
    public $flg_discount = false;
    public $price_total;

    public function render(ExtPromotionOutputsService $calc_outs)
    {

        $result = $calc_outs->calculate($this->site, $this->days, $this->flg_discount);

        $this->price_total = $result['price_total'];


        return view('livewire.portal.ext-promotion-calc');
    }
}
