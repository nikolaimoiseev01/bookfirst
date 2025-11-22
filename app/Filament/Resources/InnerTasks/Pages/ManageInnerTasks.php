<?php

namespace App\Filament\Resources\InnerTasks\Pages;

use App\Filament\Resources\InnerTasks\InnerTaskResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageInnerTasks extends ManageRecords
{
    protected static string $resource = InnerTaskResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
