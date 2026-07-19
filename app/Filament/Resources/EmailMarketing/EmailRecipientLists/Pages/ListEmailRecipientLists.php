<?php

namespace App\Filament\Resources\EmailMarketing\EmailRecipientLists\Pages;

use App\Filament\Resources\EmailMarketing\EmailRecipientLists\EmailRecipientListResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListEmailRecipientLists extends ListRecords
{
    protected static string $resource = EmailRecipientListResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
