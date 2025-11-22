<?php

namespace App\Http\Livewire\Account\CollectionParticipation\PartPageBlocks;

use App\Service\PartPageBlockStatus;
use Livewire\Component;

class PreviewBlock extends Component
{
    public $participation;
    public $collection;
    public $color;
    public $status_icon;
    public $page_style;
    public $page_title;

    public function render()
    {
        return view('livewire.account.collection-participation.part-page-blocks.preview-block');
    }

    public function mount(PartPageBlockStatus $status)
    {
        $this->collection = $this->participation->collection;

        if ($this->collection['col_status_id'] === 1 || ($this->participation->collection['col_status_id'] >= 2 && !($this->participation['paid_at'] ?? null))) {
            $this->color = 'grey';
            $this->page_title = 'Предварительная проверка';
        } elseif ($this->collection['col_status_id'] === 2) {
            $this->color = 'yellow';
            $this->page_title = 'Предварительная проверка';
        } elseif ($this->collection['col_status_id'] >= 3) {
            $this->color = 'green';
            $this->page_title = 'Предварительная проверка завершена';
        };

        $found_status = $status->get_status('.preview_block_wrap', $this->color);

        $this->status_icon = $found_status['status_icon'];
        $this->page_style = $found_status['page_style'];;
    }
}
