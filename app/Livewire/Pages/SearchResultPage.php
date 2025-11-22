<?php

namespace App\Livewire\Pages;

use App\Models\Collection\Collection;
use App\Models\OwnBook\OwnBook;
use App\Models\User\User;
use App\Models\Work\Work;
use Livewire\Component;

class SearchResultPage extends Component
{
    public string $search_request = '';

    public $ownBooks;
    public $collections;
    public $users;
    public $works;

    public function render()
    {
        return view('livewire.pages.search-result-page');
    }

    protected $queryString = [
        'search_request' => ['except' => ''],
    ];

    public function mount() {
        $this->ownBooks = OwnBook::where(function ($query) {
            $query->where('title', 'like', '%' . $this->search_request . '%')
                ->orWhere('author', 'like', '%' . $this->search_request . '%');
        })->with('media')->get();
        $this->collections = Collection::where('title', 'like', '%' . $this->search_request . '%')->orderBy('created_at', 'desc')->get();
        $this->users = User::where(function ($query) {
            $query->where('name', 'like', '%' . $this->search_request . '%')
                ->orWhere('surname', 'like', '%' . $this->search_request . '%')
                ->orWhere('nickname', 'like', '%' . $this->search_request . '%');
        })->with('media')->withCount('ownBooks', 'works', 'subscribers', 'participations')->get();
        $this->works = Work::where('title', 'like', '%' . $this->search_request . '%')->get();
    }
}
