<?php

namespace App\Http\Livewire\Portal;

use App\Models\own_book;
use Livewire\Component;

class OwnBooks extends Component
{
    public $search_input;
    public $own_books;
    public $show_clear;
    public $take_num = 9;

    public $total_cnt;
    public $loaded_cnt;

    protected $queryString = ['search_input'];

    public function render()
    {
        $this->own_books = own_book::where('own_book_status_id', 9)
            ->where(function ($query) {
                $query->where('author', 'like', '%' . $this->search_input . '%')
                    ->orWhere('title', 'like', '%' . $this->search_input . '%');
            })->orderBy('id', 'desc')->get();
        $this->total_cnt = count($this->own_books);
        $this->own_books = $this->own_books->take($this->take_num);
        $this->loaded_cnt = count($this->own_books);
        return view('livewire.portal.own-books');
    }


    public function search()
    {
        $this->take_num = 9;
        $this->dispatchBrowserEvent('trigger_all_js');
    }

    public function clear_search()
    {
        $this->take_num = 9;
        $this->search_input = null;
        $this->dispatchBrowserEvent('trigger_all_js');
    }

    public function load_more()
    {
        $this->take_num += 9;
        $this->dispatchBrowserEvent('trigger_all_js');
    }
}
