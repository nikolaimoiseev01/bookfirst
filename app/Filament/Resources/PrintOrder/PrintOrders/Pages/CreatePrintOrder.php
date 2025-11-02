<?php

namespace App\Filament\Resources\PrintOrder\PrintOrders\Pages;

use App\Filament\Resources\PrintOrder\PrintOrders\PrintOrderResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePrintOrder extends CreateRecord
{
    protected static string $resource = PrintOrderResource::class;
}
