<?php

namespace App\Filament\Resources\Chat\Chats\Pages;

use App\Filament\Resources\Chat\Chats\ChatResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListChats extends ListRecords
{
    protected static string $resource = ChatResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
