<?php

namespace App\Jobs;

use App\Models\Ai\AiAssistantConversation;
use App\Services\Ai\AiImageGenerator;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Throwable;

class GenerateAiAssistantImageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 1;
    public int $timeout = 200;

    public function __construct(public int $conversationId, public bool $paidAttempt = false)
    {
    }

    public function handle(AiImageGenerator $generator): void
    {
        $conversation = AiAssistantConversation::find($this->conversationId);
        if (!$conversation || $conversation->status !== 'pending') return;

        $conversation->update(['status' => 'running', 'started_at' => now()]);
        $imagePath = null;

        try {
            $generation = $generator->generate($conversation->question);
            $imagePath = 'ai-generated/'.$conversation->user_id.'/'.$generation->fileName;
            if (!Storage::disk('public')->put($imagePath, $generation->imageBytes)) {
                throw new \RuntimeException('Не удалось сохранить изображение. Попробуйте позже.');
            }

            $conversation->update([
                'answer' => 'Изображение создано.',
                'model' => config('services.openrouter.image_model'),
                'openrouter_cost_usd' => $generation->costUsd,
                'openrouter_generation_id' => $generation->generationId,
                'image_path' => $imagePath,
                'status' => 'completed',
                'completed_at' => now(),
            ]);
        } catch (Throwable $exception) {
            if ($imagePath) Storage::disk('public')->delete($imagePath);
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
                : 'Не удалось создать изображение. Попробуйте позже.',
            'completed_at' => now(),
        ]);

        if ($this->paidAttempt) {
            DB::table('users')->where('id', $conversation->user_id)->increment('ai_attempts_purchased');
        }

        report($exception);
    }
}
