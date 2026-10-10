<?php

namespace App\Services\Ai;

final class AiGenerationResult
{
    public function __construct(
        public readonly string $text,
        public readonly ?string $costUsd,
        public readonly ?string $generationId,
    ) {
    }
}
