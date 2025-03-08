<?php

namespace App\Filament\Resources\OwnBookResource\Pages;

use App\Filament\Resources\OwnBookResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListOwnBooks extends ListRecords
{
    protected static string $resource = OwnBookResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
