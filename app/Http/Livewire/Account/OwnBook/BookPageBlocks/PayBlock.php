<?php

namespace App\Http\Livewire\Account\OwnBook\BookPageBlocks;

use App\Service\PartPageBlockStatus;
use Livewire\Component;

class PayBlock extends Component
{
    public $own_book;
    public $status_color;
    public $status_icon;
    public $page_style;
    public $page_title;

    protected $listeners = ['refreshOwnBookPayBlock' => '$refresh'];

    public function render()
    {
        return view('livewire.account.own-book.book-page-blocks.pay-block');
    }

    public function mount(PartPageBlockStatus $status)
    {

        if($this->own_book['own_book_status_id'] === 1) {
            $this->status_color = 'grey';
            $this->page_title = 'Оплата издания';
        } elseif($this->own_book['own_book_status_id'] === 2) {
            $this->status_color = 'yellow';
            $this->page_title = 'Оплата издания';
        } elseif($this->own_book['own_book_status_id'] === 99) {
            $this->status_color = 'grey';
            $this->page_title = 'Оплата издания';
        }  else {
            $this->status_color = 'green';
            $this->page_title = 'Оплата успешно подтверждена';
        }


        $found_status = $status->get_status('.pay_block_wrap', $this->status_color);

        $this->status_icon = $found_status['status_icon'];
        $this->page_style = $found_status['page_style'];;
    }
}
