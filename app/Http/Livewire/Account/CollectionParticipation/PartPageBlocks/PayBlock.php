<?php

namespace App\Http\Livewire\Account\CollectionParticipation\PartPageBlocks;

use App\Models\Transaction;
use App\Service\PartPageBlockStatus;
use Livewire\Component;

class PayBlock extends Component
{
    public $participation;
    public $status_color;
    public $status_icon;
    public $page_style;
    public $page_title;
    public $already_paid_amount = 0;


    public function render()
    {
        return view('livewire.account.collection-participation.part-page-blocks.pay-block');
    }

    public function mount(PartPageBlockStatus $status)
    {

        $this->already_paid_amount = $this->participation->transaction->where('status', 'CONFIRMED')->sum('amount');

        if ($this->participation['pat_status_id'] === 1 || ($this->participation->collection['col_status_id'] >= 2 && !($this->participation['paid_at'] ?? null))) { // Только создал заявку или не успел оплатить
            $this->status_color = 'grey';
            $this->page_title = 'Оплата участия';
        } elseif ($this->participation['pat_status_id'] === 2) { // Нужна оплата (
            $this->status_color = 'yellow';
            $this->page_title = 'Оплата участия';
        } elseif ($this->participation['pat_status_id'] === 3) { // Успешно оплатил
            $this->status_color = 'green';
            $this->page_title = 'Оплата успешно подтверждена';
        } elseif ($this->participation['pat_status_id'] === 99) { // Успешно оплатил
            $this->status_color = 'grey';
            $this->page_title = 'Оплата участия';
        };

        $found_status = $status->get_status('.pay_block_wrap', $this->status_color);


        $this->status_icon = $found_status['status_icon'];
        $this->page_style = $found_status['page_style'];;
    }

}
