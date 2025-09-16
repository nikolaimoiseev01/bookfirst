<?php

namespace App\Livewire\Pages\Portal;

use App\Models\OwnBook\OwnBook;
use Livewire\Component;

class OwnBookPage extends Component
{
    public $ownBook;
    public $info;
    public $tabs;

    public function render()
    {
        return view('livewire.pages.portal.own-book-page');
    }

    public function mount($slug)
    {
        $this->ownBook = OwnBook::where('slug', $slug)->with('user')->with(['media', 'user.media'])->with('printOrders')->first();
        $this->info = [
            'Кол-во страниц' => $this->ownBook['pages'],
            'Первоначальный тираж' => ($this->ownBook->printOrders[0]['books_cnt'] ?? 0) + 16,
            'Обложка' => $this->ownBook->printOrders[0]['cover_type'] ?? 'Твердая',
            'Внутренний блок' => $this->ownBook->printOrders[0]['inside_type'] ?? 'Черно-белый',
        ];
        $this->tabs = [
            'default' => 'read_part',
            'tabs' => [
                'read_part' => 'Читать фрагмент'
            ]
        ];
    }
}
