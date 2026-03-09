<?php

namespace App\Filament\Resources\Chat\Messages\Pages;

use App\Filament\Resources\Chat\Messages\MessageResource;
use Filament\Resources\Pages\CreateRecord;

class CreateMessage extends CreateRecord
{
    protected static string $resource = MessageResource::class;
}
