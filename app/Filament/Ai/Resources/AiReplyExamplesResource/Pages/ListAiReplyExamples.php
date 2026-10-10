<?php

namespace App\Filament\Ai\Resources\AiReplyExamplesResource\Pages;

use App\Filament\Ai\Resources\AiReplyExamplesResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListAiReplyExamples extends ListRecords
{
    protected static string $resource = AiReplyExamplesResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
