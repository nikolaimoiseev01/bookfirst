<?php

namespace App\Filament\Resources\Chat\MessageTemplates\Pages;

use App\Filament\Resources\Chat\MessageTemplates\MessageTemplatesResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageMessageTemplates extends ManageRecords
{
    protected static string $resource = MessageTemplatesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
