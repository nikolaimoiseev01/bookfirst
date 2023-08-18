<?php

namespace App\Http\Livewire\Account\OwnBook\BookPageBlocks;

use App\Models\digital_sale;
use App\Service\PartPageBlockStatus;
use Livewire\Component;

class SalesBlock extends Component
{
    public $own_book;
    public $status_color;
    public $status_icon;
    public $page_style;
    public $digital_sales;

    public function render()
    {
        $this->digital_sales = digital_sale::where('bought_own_book_id', $this->own_book['id'])->get();
        return view('livewire.account.own-book.book-page-blocks.sales-block');
    }

    public function mount(PartPageBlockStatus $status) {

        if($this->own_book['own_book_status_id'] < 9) {
            $this->status_color = 'grey';
        } else {
            $this->status_color = 'green';
        }

        $found_status = $status->get_status('.sales_block_wrap', $this->status_color);

        $this->status_icon = $found_status['status_icon'];
        $this->page_style = $found_status['page_style'];;
    }


}
