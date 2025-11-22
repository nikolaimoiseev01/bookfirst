<?php

namespace App\Http\Livewire\Portal;

use App\Service\OwnBookOutputsService;
use Livewire\Component;

class OwnbookCalc extends Component
{
    public $pages = "100";


    public $inside_ready = '0';
    public $need_design = true;
    public $need_check = false;

    public $cover_ready = '0';

    public $need_print = false;
    public $prints = 10;
    public $cover_type = 'soft';
    public $inside_color = '0';
    public $pages_color = '0';

    public $price_inside;
    public $price_design;
    public $price_check;
    public $price_cover;
    public $price_print;
    public $price_promo;
    public $price_total;

    public $need_promo;
    public $promo_type = "1";

    public function render(OwnBookOutputsService $calc_outs)
    {
        if ($this->inside_ready === '1') {
            $this->need_design = false;
            $this->need_check = false;
        }

        if (!$this->need_promo) {
            $this->promo_type = null;
        } elseif(!$this->promo_type) {
            $this->promo_type = '1';
        }

        if ($this->inside_color == "0") {
            $this->pages_color = 0;
        }

        // Узнаем цены участия
        $result = $calc_outs->calculate(
            $this->pages,
            $this->pages_color,
            $this->need_design,
            $this->need_check,
            $this->cover_ready,
            $this->need_print,
            $this->prints,
            $this->cover_type,
            $this->promo_type
        );
        $this->price_inside = $result['price_inside'];
        $this->price_design = $result['price_design'];
        $this->price_check = $result['price_check'];
        $this->price_cover = $result['price_cover'];
        $this->price_print = $result['price_print'];
        $this->price_promo = $result['price_promo'];
        $this->price_total = $result['price_total'];

        return view('livewire.portal.ownbook-calc');
    }


}
