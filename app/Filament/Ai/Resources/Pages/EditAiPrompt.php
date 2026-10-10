<?php

namespace App\Filament\Ai\Resources\Pages;

use App\Filament\Ai\Resources\AiPromptsResource;
use Filament\Resources\Pages\EditRecord;

class EditAiPrompt extends EditRecord
{
    protected static string $resource = AiPromptsResource::class;
}
