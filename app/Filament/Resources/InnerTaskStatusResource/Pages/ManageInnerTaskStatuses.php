<?php

namespace App\Filament\Resources\InnerTaskStatusResource\Pages;

use App\Filament\Resources\InnerTaskStatusResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageInnerTaskStatuses extends ManageRecords
{
    protected static string $resource = InnerTaskStatusResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
