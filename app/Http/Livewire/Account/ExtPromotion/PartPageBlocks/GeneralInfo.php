<?php

namespace App\Http\Livewire\Account\ExtPromotion\PartPageBlocks;

use App\Service\PartPageBlockStatus;
use Livewire\Component;

class GeneralInfo extends Component
{
    public $ext_promotion;

    public $status_color;
    public $status_color_shadow;
    public $status_icon;
    public $page_style;
    public $app_text;

    public function render()
    {
        return view('livewire.account.ext-promotion.part-page-blocks.general-info');

    }

    public function mount(PartPageBlockStatus $status) {
        if(1 === 1) {
            $color = 'green';
        }

        $found_status = $status->get_status('.general_info_wrap', $color);

        $this->status_color = $found_status['status_color'];
        $this->status_color_shadow = $found_status['status_color_shadow'];
        $this->status_icon = $found_status['status_icon'];
        $this->page_style = $found_status['page_style'];;
    }
}
