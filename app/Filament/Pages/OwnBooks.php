<?php

namespace App\Filament\Pages;

use App\Models\own_book;
use Filament\Pages\Page;

class OwnBooks extends Page
{
    public $own_books;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.own-books';

    public function mount(): void
    {
        $this->own_books = own_book::orderBy('id', 'desc')->get();
    }


}
