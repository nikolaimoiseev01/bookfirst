<?php

namespace App\Filament\Resources\Collection\Participations\Pages;

use App\Filament\Resources\Collection\Participations\ParticipationResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditParticipation extends EditRecord
{
    protected static string $resource = ParticipationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
