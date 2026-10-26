<?php

namespace App\Jobs;

use App\Models\Ai\AiAssistantConversation;
use App\Services\Ai\AIGenerator;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Throwable;

class GenerateAiAssistantTextJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 1;
    public int $timeout = 90;

    public function __construct(
        public int $conversationId,
        public string $systemPrompt,
        public string $userPrompt,
        public bool $paidAttempt = false,
    ) {
    }

    public function handle(AIGenerator $generator): void
    {
        $conversation = AiAssistantConversation::find($this->conversationId);
        if (!$conversation || $conversation->status !== 'pending') return;

        $conversation->update(['status' => 'running', 'started_at' => now()]);

        try {
            $generation = $generator->generateWithUsage($this->systemPrompt, $this->userPrompt);
            $conversation->update([
                'answer' => $generation->text,
                'model' => config('services.openrouter.model'),
                'openrouter_cost_usd' => $generation->costUsd,
                'openrouter_generation_id' => $generation->generationId,
                'status' => 'completed',
                'completed_at' => now(),
            ]);
        } catch (Throwable $exception) {
            $this->markFailed($conversation, $exception);
        }
    }

    public function failed(Throwable $exception): void
    {
        $conversation = AiAssistantConversation::find($this->conversationId);
        if ($conversation) $this->markFailed($conversation, $exception);
    }

    private function markFailed(AiAssistantConversation $conversation, Throwable $exception): void
    {
        $conversation->update([
            'status' => 'failed',
            'error_message' => $exception instanceof \RuntimeException
                ? $exception->getMessage()
                : 'Не удалось обработать запрос. Попробуйте позже.',
            'completed_at' => now(),
        ]);

        if ($this->paidAttempt) {
            DB::table('users')->where('id', $conversation->user_id)->increment('ai_attempts_purchased');
        }

        report($exception);
    }
}
