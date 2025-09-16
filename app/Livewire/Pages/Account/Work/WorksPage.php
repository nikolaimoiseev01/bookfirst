<?php

namespace App\Livewire\Pages\Account\Work;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class WorksPage extends Component
{
    use WithPagination;
    public $searchText;

    public function render()
    {
        return view('livewire.pages.account.work.works-page', [
            'works' => Auth::user()->works()->where('title', 'like', '%' . $this->searchText . '%')->cursorPaginate(10)
        ])->layout('layouts.account');
    }

    public function search()
    {

    }

    public function clearSearch()
    {
        $this->searchText = null;
    }
}
