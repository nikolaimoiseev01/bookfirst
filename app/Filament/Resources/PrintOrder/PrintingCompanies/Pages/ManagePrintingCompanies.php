<?php

namespace App\Filament\Resources\PrintOrder\PrintingCompanies\Pages;

use App\Filament\Resources\PrintOrder\PrintingCompanies\PrintingCompanyResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManagePrintingCompanies extends ManageRecords
{
    protected static string $resource = PrintingCompanyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
