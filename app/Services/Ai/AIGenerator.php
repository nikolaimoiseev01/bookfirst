<?php

namespace App\Services\Ai;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use RuntimeException;

class AIGenerator
{
    public function generate(string $systemPrompt, string $userPrompt, int $maxTokens = 500): string
    {
        return $this->generateWithUsage($systemPrompt, $userPrompt, $maxTokens)->text;
    }

    public function generateWithUsage(string $systemPrompt, string $userPrompt, int $maxTokens = 500): AiGenerationResult
    {
        $apiKey = config('services.openrouter.api_key');

        if (!$apiKey) {
            throw new RuntimeException('ИИ-помощник пока не настроен. Попробуйте позже.');
        }

        try {
            $response = Http::acceptJson()
                ->withToken($apiKey)
                ->withHeaders([
                    'HTTP-Referer' => config('app.url'),
                    'X-Title' => config('app.name'),
                ])
                ->connectTimeout(10)
                ->timeout(60)
                ->post('https://openrouter.ai/api/v1/chat/completions', [
                    'model' => config('services.openrouter.model'),
                    'messages' => [
                        [
                            'role' => 'system',
                            'content' => $systemPrompt,
                        ],
                        [
                            'role' => 'user',
                            'content' => $userPrompt,
                        ],
                    ],
                    'temperature' => 0.7,
                    'max_tokens' => $maxTokens,
                    'usage' => ['include' => true],
                ]);
        } catch (ConnectionException $exception) {
            Log::error('OpenRouter request failed before receiving a response.', [
                'model' => config('services.openrouter.model'),
                'exception' => $exception->getMessage(),
            ]);

            throw new RuntimeException('Не удалось получить ответ от ИИ. Попробуйте позже.', previous: $exception);
        }

        if (!$response->successful()) {
            Log::error('OpenRouter returned an unsuccessful response.', [
                'status' => $response->status(),
                'model' => config('services.openrouter.model'),
                'request_id' => $response->header('X-Request-Id'),
                'body' => mb_substr($response->body(), 0, 4000),
            ]);

            throw new RuntimeException('Не удалось получить ответ от ИИ. Попробуйте позже.');
        }

        $result = trim((string) $response->json('choices.0.message.content'));

        if ($result === '') {
            Log::warning('OpenRouter returned a successful response without message content.', [
                'status' => $response->status(),
                'model' => config('services.openrouter.model'),
                'request_id' => $response->header('X-Request-Id'),
                'body' => mb_substr($response->body(), 0, 4000),
            ]);

            throw new RuntimeException('ИИ вернул пустой ответ. Попробуйте еще раз.');
        }

        $cost = $response->json('usage.cost');
        $costUsd = is_numeric($cost) && (float) $cost >= 0 ? (string) $cost : null;

        return new AiGenerationResult(
            text: $result,
            costUsd: $costUsd,
            generationId: $response->json('id') ?: $response->header('X-Generation-Id'),
        );
    }
}
