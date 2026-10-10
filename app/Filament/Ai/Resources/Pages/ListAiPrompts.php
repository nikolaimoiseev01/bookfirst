<?php

namespace App\Filament\Ai\Resources\Pages;

use App\Filament\Ai\Resources\AiPromptsResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListAiPrompts extends ListRecords
{
    protected static string $resource = AiPromptsResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
