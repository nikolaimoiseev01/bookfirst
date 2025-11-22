<?php

namespace App\Http\Livewire\Account\ExtPromotion\PartPageBlocks;

use App\Service\PartPageBlockStatus;
use Livewire\Component;

class PayBlock extends Component
{

    public $ext_promotion;
    public $status_color;
    public $status_icon;
    public $page_style;
    public $page_title;
    public $already_paid_amount = 0;

    public function render()
    {
        return view('livewire.account.ext-promotion.part-page-blocks.pay-block');
    }

    public function mount(PartPageBlockStatus $status) {

        if ($this->ext_promotion['ext_promotion_status_id'] === 1) { // Только создал заявку или не успел оплатить
            $this->status_color = 'grey';
            $this->page_title = 'Оплата продвижения';
        } elseif ($this->ext_promotion['ext_promotion_status_id'] === 2) { // Нужна оплата (
            $this->status_color = 'yellow';
            $this->page_title = 'Оплата продвижения';
        } elseif ($this->ext_promotion['ext_promotion_status_id'] > 9) { // Ожидание автора в чате или неакутально
            $this->status_color = 'grey';
            $this->page_title = 'Оплата продвижения';
        } elseif ($this->ext_promotion['ext_promotion_status_id'] >= 3) { // Успешно оплатил
            $this->status_color = 'green';
            $this->page_title = 'Оплата успешно подтверждена';
        } ;

        $found_status = $status->get_status('.pay_block_wrap', $this->status_color);


        $this->status_icon = $found_status['status_icon'];
        $this->page_style = $found_status['page_style'];
    }
}
