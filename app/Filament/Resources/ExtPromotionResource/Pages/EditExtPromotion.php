<?php

namespace App\Filament\Resources\ExtPromotionResource\Pages;

use App\Filament\Resources\ExtPromotionResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditExtPromotion extends EditRecord
{
    protected static string $resource = ExtPromotionResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
