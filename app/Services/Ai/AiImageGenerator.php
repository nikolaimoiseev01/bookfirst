<?php

namespace App\Services\Ai;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use RuntimeException;

class AiImageGenerator
{
    public function generate(string $prompt): AiImageGenerationResult
    {
        $apiKey = config('services.openrouter.api_key');
        $model = config('services.openrouter.image_model');

        if (!$apiKey || !$model) {
            throw new RuntimeException('Генерация изображений пока не настроена. Попробуйте позже.');
        }

        try {
            $response = Http::acceptJson()
                ->withToken($apiKey)
                ->withOptions(['proxy' => config('services.telegram-proxy')])
                ->withHeaders([
                    'HTTP-Referer' => config('app.url'),
                    'X-Title' => config('app.name'),
                ])
                ->connectTimeout(30)
                ->timeout(180)
                ->post('https://openrouter.ai/api/v1/images', [
                    'model' => $model,
                    'prompt' => $prompt,
                ]);
        } catch (ConnectionException $exception) {
            Log::error('OpenRouter image request could not connect.', [
                'model' => $model,
                'proxy_configured' => (bool) config('services.telegram-proxy'),
                'exception' => $exception->getMessage(),
            ]);

            throw $exception;
        }

        if (!$response->successful()) {
            Log::error('OpenRouter image request returned an error response.', [
                'status' => $response->status(),
                'model' => $model,
                'body' => mb_substr($response->body(), 0, 2000),
            ]);

            throw new RuntimeException('Не удалось создать изображение. Попробуйте позже.');
        }

        $encodedImage = $response->json('data.0.b64_json');
        $imageBytes = is_string($encodedImage) ? base64_decode($encodedImage, true) : false;
        if ($imageBytes === false || $imageBytes === '') {
            throw new RuntimeException('ИИ не вернул изображение. Попробуйте еще раз.');
        }

        $mediaType = $response->json('data.0.media_type', 'image/png');
        $extension = match ($mediaType) {
            'image/png' => 'png',
            'image/jpeg' => 'jpg',
            'image/webp' => 'webp',
            default => throw new RuntimeException('ИИ вернул неподдерживаемый формат изображения.'),
        };

        $cost = $response->json('usage.cost');

        return new AiImageGenerationResult(
            imageBytes: $imageBytes,
            fileName: Str::uuid().'.'.$extension,
            costUsd: is_numeric($cost) && (float) $cost >= 0 ? (string) $cost : null,
            generationId: $response->json('id') ?: $response->header('X-Generation-Id'),
        );
    }
}
