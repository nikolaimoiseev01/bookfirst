<?php

namespace App\Filament\Resources\Collection\Collections\Pages;

use App\Enums\CollectionStatusEnums;
use App\Filament\Resources\Collection\Collections\CollectionResource;
use App\Jobs\CollectionGenerate3dJob;
use App\Services\CollectionCoverPngService;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Log;

class CreateCollection extends CreateRecord
{
    protected static string $resource = CollectionResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $wordsToRemove = ['Современный ', ' Поэзии', 'Сокровенные ', 'Выпуск '];
        $titleShort = str_replace($wordsToRemove, '', $data['title']);

        if(str_contains($titleShort, 'Дух')){
            $data['work_type_id'] = 2;
        }
        if(str_contains($titleShort, 'Мысли')){
            $data['work_type_id'] = 1;
        }

        $data['title_short'] = $titleShort;
        $data['slug'] = str($titleShort)->slug()->lower();
        $data['status'] = \App\Enums\CollectionStatusEnums::APPS_IN_PROGRESS;

        return $data;
    }

    protected function afterCreate(): void
    {
        CollectionGenerate3dJob::dispatch($this->record->id);
    }
}
