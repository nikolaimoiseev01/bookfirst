<?php

namespace App\Filament\Resources\Collection\ParticipationResource\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\Collection\ParticipationResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListParticipations extends ListRecords
{
    protected static string $resource = ParticipationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
