<?php

namespace App\Services\Ai;

final class AiImageGenerationResult
{
    public function __construct(
        public readonly string $imageBytes,
        public readonly string $fileName,
        public readonly ?string $costUsd,
        public readonly ?string $generationId,
    ) {
    }
}
