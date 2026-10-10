<?php

namespace App\Filament\Ai\Resources\AiKnowledgeDocumentsResource\Pages;

use App\Filament\Ai\Resources\AiKnowledgeDocumentsResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListAiKnowledgeDocuments extends ListRecords
{
    protected static string $resource = AiKnowledgeDocumentsResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
