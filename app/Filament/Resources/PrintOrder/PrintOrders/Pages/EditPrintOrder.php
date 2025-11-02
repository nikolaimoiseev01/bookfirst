<?php

namespace App\Filament\Resources\PrintOrder\PrintOrders\Pages;

use App\Filament\Resources\PrintOrder\PrintOrders\PrintOrderResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditPrintOrder extends EditRecord
{
    protected static string $resource = PrintOrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
