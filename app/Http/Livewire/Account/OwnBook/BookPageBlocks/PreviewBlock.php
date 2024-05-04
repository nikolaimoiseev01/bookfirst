<?php

namespace App\Http\Livewire\Account\OwnBook\BookPageBlocks;

use App\Models\preview_comment;
use App\Service\OwnBookPreviewTexts;
use App\Service\PartPageBlockStatus;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;
use Livewire\Component;

class PreviewBlock extends Component
{
    public $own_book;
    public $color;
    public $status_icon;
    public $page_style;
    public $page_title;
    public $text;

    public $status_id;
    public $status_title;

    public $chosen_type = 'inside';
    public $comments;


    public function render(OwnBookPreviewTexts $text_response)
    {
        $this->dispatchBrowserEvent('trigger_all_js');
        // Чтобы обращаться к статусу выбранного типа
        if($this->chosen_type == 'inside') {
            $this->status_id = $this->own_book['own_book_inside_status_id'];
            $this->status_title = $this->own_book->own_book_inside_status['status_title'];
        } else {
            $this->status_id = $this->own_book['own_book_cover_status_id'];
            $this->status_title = $this->own_book->own_book_cover_status['status_title'];
        }

        // Получаем все тексты для блока
        $this->text = $text_response->get_text(
            $this->own_book,
            $this->chosen_type,
            ($this->chosen_type == 'inside') ? $this->own_book['own_book_inside_status_id'] : $this->own_book['own_book_cover_status_id']
        );

        $this->comments = preview_comment::where([['user_id', Auth::user()->id], ['own_book_id', $this->own_book['id']], ['own_book_comment_type', $this->chosen_type]])->get();

        return view('livewire.account.own-book.book-page-blocks.preview-block');
    }

    public function mount(PartPageBlockStatus $status)
    {
        $own_book_inside_status_id = $this->own_book['own_book_inside_status_id'];
        $own_book_cover_status_id = $this->own_book['own_book_cover_status_id'];
        if ($this->own_book['own_book_status_id'] < 3) { // Если еще не началась работа после оплаты
            $this->color = 'grey';
            $this->page_title = 'Предварительная проверка';
        } elseif ($this->own_book['own_book_status_id'] == 9) { // Если еще не началась работа после оплаты
            $this->color = 'grey';
            $this->page_title = 'Предварительная проверка';
        } elseif (($own_book_inside_status_id == 1 || $own_book_inside_status_id == 9) && ($own_book_cover_status_id == 1 || $own_book_cover_status_id == 9)) { // Если идет разработка
            $this->color = 'grey';
            $this->page_title = 'Предварительная проверка';
        } elseif ($this->own_book['own_book_inside_status_id'] < 4 || $this->own_book['own_book_cover_status_id'] < 4) { // Если обложка ИЛИ макет в работе
            $this->color = 'yellow';
            $this->page_title = 'Предварительная проверка';
        } elseif ($this->own_book['own_book_inside_status_id'] == 99 || $this->own_book['own_book_cover_status_id'] == 99) { // Если обложка ИЛИ макет в ожидании ответа от автора
            $this->color = 'yellow';
            $this->page_title = 'Предварительная проверка';
        } else {
            $this->color = 'green';
            $this->page_title = 'Предварительная проверка завершена';
        };

        $found_status = $status->get_status('.preview_block_wrap', $this->color);

        $this->status_icon = $found_status['status_icon'];
        $this->page_style = $found_status['page_style'];;
    }
}
