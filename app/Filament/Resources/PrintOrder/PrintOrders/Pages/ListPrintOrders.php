<?php

namespace App\Filament\Resources\PrintOrder\PrintOrders\Pages;

use App\Filament\Resources\PrintOrder\PrintOrders\PrintOrderResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPrintOrders extends ListRecords
{
    protected static string $resource = PrintOrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
