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

    public function getTitle(): string
    {
        $author_name = $this->record['author_name'];
        $collection_title = $this->record->collection['title_short'];
        return "Участие {$author_name} в {$collection_title}";
    }
}
