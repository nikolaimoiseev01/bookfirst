<?php

namespace App\Filament\Resources\PrintOrder\LogisticCompanies\Pages;

use App\Filament\Resources\PrintOrder\LogisticCompanies\LogisticCompanyResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageLogisticCompanies extends ManageRecords
{
    protected static string $resource = LogisticCompanyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
