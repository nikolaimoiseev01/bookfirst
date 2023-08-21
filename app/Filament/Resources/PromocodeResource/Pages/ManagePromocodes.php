<?php

namespace App\Filament\Resources\PromocodeResource\Pages;

use App\Filament\Resources\PromocodeResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManagePromocodes extends ManageRecords
{
    protected static string $resource = PromocodeResource::class;

    protected static ?string $title = 'Промокоды';

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
