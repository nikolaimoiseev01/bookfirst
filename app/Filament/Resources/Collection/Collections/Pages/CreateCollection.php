<?php

namespace App\Filament\Resources\Collection\Collections\Pages;

use App\Enums\CollectionStatusEnums;
use App\Filament\Resources\Collection\Collections\CollectionResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCollection extends CreateRecord
{
    protected static string $resource = CollectionResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $wordsToRemove = ['Современный ', ' Поэзии', 'Сокровенные ', 'Выпуск '];
        $titleShort = str_replace($wordsToRemove, '', $data['title']);

        $data['title_short'] = $titleShort;
        $data['slug'] = str($titleShort)->slug()->lower();
        $data['status'] = \App\Enums\CollectionStatusEnums::APPS_IN_PROGRESS;

        return $data;
    }
}
