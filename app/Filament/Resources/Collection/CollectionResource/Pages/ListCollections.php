<?php

namespace App\Filament\Resources\Collection\CollectionResource\Pages;

use App\Filament\Resources\Collection\CollectionResource;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListCollections extends ListRecords
{
    protected static string $resource = CollectionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected static ?string $title = 'Сборники';

//    public function getTabs(): array
//    {
//        return [
//            'Актуальные' => Tab::make()
//                ->modifyQueryUsing(fn (Builder $query) => $query->where('collection_status_id', '<', 9)),
//            'Закрытые' => Tab::make()
//                ->modifyQueryUsing(fn (Builder $query) => $query->where('collection_status_id', 9)),
//        ];
//    }
//    public function getDefaultActiveTab(): string | int | null
//    {
//        return 'Актуальные';
//    }
}
