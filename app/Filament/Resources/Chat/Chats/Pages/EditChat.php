<?php

namespace App\Filament\Resources\Chat\Chats\Pages;

use App\Filament\Resources\Chat\Chats\ChatResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditChat extends EditRecord
{
    protected static string $resource = ChatResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
