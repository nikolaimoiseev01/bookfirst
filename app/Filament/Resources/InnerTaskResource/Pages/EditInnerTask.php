<?php

namespace App\Filament\Resources\InnerTaskResource\Pages;

use App\Filament\Resources\InnerTaskResource;
use App\Models\Collection;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Contracts\Support\Htmlable;

class EditInnerTask extends EditRecord
{
    protected static string $resource = InnerTaskResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
    public function getTitle(): string
    {
        $record = $this->record;
        if ($record['own_book_id']) {
            return  "{$record['title'] } {$record->own_book['author']}: {$record->own_book['title']}";
        } elseif ($record['collection_id']) {
            $collection = Collection::find($record['collection_id']);
            $title_short = str_replace(array('Современный', 'Поэзии', 'Сокровенные', '.', ' '), "", $collection->title);
            $title_short = str_replace(array('Выпуск'), " ", $title_short);
            return "{$record['title'] } $title_short";
        }
        return 'Редактирование';
    }

}
