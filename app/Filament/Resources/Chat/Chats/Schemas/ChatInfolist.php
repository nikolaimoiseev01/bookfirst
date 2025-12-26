<?php

namespace App\Filament\Resources\Chat\Chats\Schemas;

use Filament\Schemas\Components\Livewire;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ChatInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()->schema([
                    Livewire::make('components.account.chat', ['chat' => $schema->getRecord()])
                ])->columnSpanFull()
            ]);
    }
}
