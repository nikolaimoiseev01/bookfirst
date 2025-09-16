<?php

namespace App\Filament\Resources\OwnBook\OwnBooks\Pages;

use App\Filament\Resources\OwnBook\OwnBooks\OwnBookResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListOwnBooks extends ListRecords
{
    protected static string $resource = OwnBookResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
