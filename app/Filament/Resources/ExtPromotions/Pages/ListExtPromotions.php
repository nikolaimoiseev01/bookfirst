<?php

namespace App\Filament\Resources\ExtPromotions\Pages;

use App\Filament\Resources\ExtPromotions\ExtPromotionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListExtPromotions extends ListRecords
{
    protected static string $resource = ExtPromotionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
