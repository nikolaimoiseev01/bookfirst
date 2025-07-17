<?php

namespace App\Filament\Resources\Collection\CollectionResource\Pages;

use App\Filament\Resources\Collection\CollectionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCollection extends EditRecord
{
    protected static string $resource = CollectionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    public function getTitle(): string
    {
        return $this->record['name'];
    }

    public function getContentTabLabel(): ?string
    {
        return 'Сборник';
    }

    public function hasCombinedRelationManagerTabsWithContent(): bool
    {
        return true;
    }
}
