<?php

namespace App\Livewire\Pages\Account\OwnBook;

use App\Models\OwnBook\OwnBook;
use Livewire\Component;

class OwnBookPage extends Component
{
    public $ownBook;
    public function render()
    {
        return view('livewire.pages.account.own-book.own-book-page')->layout('layouts.account');
    }

    public function mount($id) {
        $this->ownBook = OwnBook::where('id', $id)->with('chat', 'works', 'media', 'ownBookStatus', 'ownBookCoverStatus', 'ownBookInsideStatus')->first();
    }
}
