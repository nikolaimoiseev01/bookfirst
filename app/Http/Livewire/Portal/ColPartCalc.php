<?php

namespace App\Http\Livewire\Portal;

use App\Service\ParticipationOutputsService;
use Livewire\Component;

class ColPartCalc extends Component
{
    public $pages = 7;
    public $need_check = false;
    public $prints = 1;
    public $flg_promo  = false;

    public $price_part;
    public $price_print;
    public $price_check;
    public $price_total;

    public function render(ParticipationOutputsService $calc_outs)
    {
        if($this->flg_promo) {
            $promo_discount = 20;
        } else {
            $promo_discount = 0;
        }

        $result = $calc_outs->calculate($this->pages, true, $this->prints, $this->need_check, $promo_discount);

        $this->price_part = $result['price_part'];
        $this->price_print = $result['price_print'];
        $this->price_check = $result['price_check'];
        $this->price_total = $result['price_total'];


        return view('livewire.portal.col-part-calc');
    }

}
