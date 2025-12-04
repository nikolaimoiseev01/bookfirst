<?php

namespace App\Livewire\Pages\Portal;

use App\Enums\OwnBookStatusEnums;
use App\Models\OwnBook\OwnBook;
use Livewire\Attributes\Url;
use Livewire\Component;

class OwnBooksReleasedPage extends Component
{
    public $take = 10;
    public $moreCnt = 10;
    public $ownBooks;
    #[Url]
    public $searchText;
    public $totalCnt;

    public function render()
    {

        return view('livewire.pages.portal.own-books-released-page');
    }

    public function resetBooks()
    {
        $this->totalCnt = OwnBook::query()
            ->where('status_general', OwnBookStatusEnums::DONE)
            ->when($this->searchText != '', function ($query) {
                $query->where('title', 'like', "%{$this->searchText}%")
                    ->orWhere('author', 'like', "%{$this->searchText}%");
            })
            ->where('title', 'like', '%' . $this->searchText . '%')->count();

        $this->take = min($this->totalCnt, $this->take);
        $this->ownBooks = OwnBook::query()
            ->where('status_general', OwnBookStatusEnums::DONE)
            ->when($this->searchText != '', function ($query) {
                $query->where('title', 'like', "%{$this->searchText}%")
                    ->orWhere('author', 'like', "%{$this->searchText}%");
            })
            ->orderBy('created_at', 'desc')
            ->take($this->take)
            ->with('user')
            ->with(['media', 'user.media'])
            ->get();
    }

    public function mount()
    {
        $this->resetBooks();
    }

    public function loadMore()
    {
        $this->take += $this->moreCnt;
        $this->resetBooks();
    }

    public function search()
    {
        $this->take = 10;
        $this->resetBooks();
    }

    public function clearSearch()
    {
        $this->searchText = null;
        $this->take = 10;
        $this->resetBooks();
    }
}
