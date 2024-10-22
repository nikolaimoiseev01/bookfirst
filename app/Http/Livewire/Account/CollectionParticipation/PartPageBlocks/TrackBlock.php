<?php

namespace App\Http\Livewire\Account\CollectionParticipation\PartPageBlocks;

use App\Service\PartPageBlockStatus;
use Livewire\Component;

class TrackBlock extends Component
{
    public $participation;
    public $collection;
    public $status_color;
    public $status_icon;
    public $page_style;

    public function render()
    {
        return view('livewire.account.collection-participation.part-page-blocks.track-block');
    }

    public function mount(PartPageBlockStatus $status)
    {
        $this->collection = $this->participation->collection;

        $col_status_id = $this->collection['col_status_id']; // Для удобства в IF'е

        if (!($this->participation->printorder ?? null) // Если нет заказа
            || $this->participation['pat_status_id'] < 3 // Если не оплатил участие
            || $col_status_id !== 9 // Если не до конца издан сборник
            || ($this->participation->collection['col_status_id'] >= 2 && !($this->participation['paid_at'] ?? null))) {
            $this->status_color = 'grey';
//        } elseif (!$this->participation->printorder['paid_at']) { // Если не оплатил пересылку
//            $this->status_color = 'yellow';
//        } else {
        }
        else {
            $this->status_color = 'green';
        };

        $found_status = $status->get_status('.track_block_wrap', $this->status_color);

        $this->status_icon = $found_status['status_icon'];
        $this->page_style = $found_status['page_style'];;
    }
}
