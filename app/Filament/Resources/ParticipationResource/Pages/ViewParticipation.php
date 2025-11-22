<?php

namespace App\Filament\Resources\ParticipationResource\Pages;

use App\Filament\Resources\ParticipationResource;
use App\Models\Participation;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewParticipation extends ViewRecord
{
    protected static string $resource = ParticipationResource::class;

    protected function getTitle(): string
    {
        $author = prefer_name($this->record['name'], $this->record['surname'], $this->record['nickname']);
        return  '<a href="vk.com">Тест</a>' . $author;
    }
}
