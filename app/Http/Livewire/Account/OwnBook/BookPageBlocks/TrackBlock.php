<?php

namespace App\Http\Livewire\Account\OwnBook\BookPageBlocks;

use App\Service\PartPageBlockStatus;
use Livewire\Component;

class TrackBlock extends Component
{
    public $own_book;
    public $status_color;
    public $status_icon;
    public $page_style;

    protected $listeners = ['refreshOwnBookTrackBlock' => '$refresh'];

    public function render()
    {

        return view('livewire.account.own-book.book-page-blocks.track-block');
    }

    public function mount(PartPageBlockStatus $status)
    {
        $own_book_status_id = $this->own_book['own_book_status_id'];
        if ($own_book_status_id < 9 && $own_book_status_id !== 4) { // Почти на всех статусах показываем серым
            $this->status_color = 'grey';
        } elseif ($own_book_status_id === 4) {
            $this->status_color = 'yellow';
        } elseif($own_book_status_id === 9 && $this->own_book->printorder['paid_at'] ?? null === null) {
            $this->status_color = 'yellow';
        } else {
            $this->status_color = 'green';
        }


        $found_status = $status->get_status('.track_block_wrap', $this->status_color);

        $this->status_icon = $found_status['status_icon'];
        $this->page_style = $found_status['page_style'];;
    }
}
