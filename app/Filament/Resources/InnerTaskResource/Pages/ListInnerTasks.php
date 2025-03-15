<?php

namespace App\Filament\Resources\InnerTaskResource\Pages;

use App\Filament\Resources\InnerTaskResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListInnerTasks extends ListRecords
{
    protected static string $resource = InnerTaskResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getTableRecordsPerPageSelectOptions(): array
    {
        return [50, 100, 150, 200];
    }
}
