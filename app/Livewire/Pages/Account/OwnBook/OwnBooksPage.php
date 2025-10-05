<?php

namespace App\Livewire\Pages\Account\OwnBook;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class OwnBooksPage extends Component
{
    use WithPagination;

    public function render()
    {
        return view('livewire.pages.account.own-book.own-books-page', [
            'own_books' => Auth::user()->ownBooks()->with(['media', 'ownBookStatus', 'ownBookInsideStatus', 'ownBookCoverStatus'])->simplePaginate(4)
        ])->layout('layouts.account');
    }
}
