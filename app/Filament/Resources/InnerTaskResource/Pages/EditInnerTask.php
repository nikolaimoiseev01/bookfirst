<?php

namespace App\Filament\Resources\InnerTaskResource\Pages;

use App\Filament\Resources\InnerTaskResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditInnerTask extends EditRecord
{
    protected static string $resource = InnerTaskResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
