<?php

namespace App\Filament\Resources\PrintOrder\PrintOrders\Pages;

use App\Filament\Resources\PrintOrder\PrintOrders\PrintOrderResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewPrintOrder extends ViewRecord
{
    protected static string $resource = PrintOrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
