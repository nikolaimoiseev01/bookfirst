<?php

namespace App\Filament\Ai\Resources\Pages;

use App\Filament\Ai\Resources\AiPromptsResource;
use Filament\Resources\Pages\CreateRecord;

class CreateAiPrompt extends CreateRecord
{
    protected static string $resource = AiPromptsResource::class;
}
