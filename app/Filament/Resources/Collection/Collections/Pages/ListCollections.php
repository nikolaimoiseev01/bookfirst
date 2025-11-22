<?php

namespace App\Filament\Resources\Collection\Collections\Pages;

use App\Filament\Resources\Collection\Collections\CollectionResource;
use App\Filament\Resources\Collection\Participations\Pages\ListParticipations;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCollections extends ListRecords
{
    protected static string $resource = CollectionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('export')
                ->label('Новые заявки')
                ->color('success')
                ->url(ListParticipations::getUrl()),
            CreateAction::make(),
        ];
    }
}
