<?php

namespace App\Livewire\Pages\Account\Ai;

use App\Models\Ai\AiPrompt;
use App\Models\Ai\AiAssistantConversation;
use App\Enums\TransactionTypeEnums;
use App\Services\PaymentService;
use App\Services\Ai\AIGenerator;
use App\Services\Ai\AiImageGenerator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Livewire\Component;
use Livewire\WithPagination;

class AiAssistantPage extends Component
{
    use WithPagination;

    public string $text = '';
    public ?int $selectedPromptId = null;
    public ?string $result = null;
    public string $imagePrompt = '';
    public ?string $generatedImageUrl = null;
    public bool $paymentReturn = false;

    public function mount(): void
    {
        $this->selectedPromptId = null;
        $this->paymentReturn = request()->query('payment') === 'ai_attempt_pack';
    }

    public function selectPrompt(int $promptId): void
    {
        if (AiPrompt::query()->whereKey($promptId)->where('is_active', true)->exists()) {
            $this->selectedPromptId = $promptId;
            $this->resetValidation();
            $this->result = null;
            $this->dispatch('close-modal', 'aiPromptPicker');
        } else {
            $this->showError('Эта задача сейчас недоступна. Выберите другую.');
        }
    }

    public function generate(AIGenerator $generator): void
    {
        $this->result = null;
        $this->resetValidation();

        try {
            $validated = $this->validate([
                'text' => ['required', 'string', 'min:5', 'max:20000'],
                'selectedPromptId' => ['required', 'integer'],
            ], [
                'text.required' => 'Заполните поле задачи.',
                'text.min' => 'Добавьте немного больше информации, чтобы ИИ мог выполнить задачу.',
                'text.max' => 'Текст должен быть не длиннее 20 000 символов.',
                'selectedPromptId.required' => 'Выберите цель.',
            ]);
        } catch (ValidationException $exception) {
            $this->resetValidation();
            $this->showError(collect($exception->errors())->flatten()->implode("\n"));
            return;
        }

        $prompt = AiPrompt::query()
            ->whereKey($validated['selectedPromptId'])
            ->where('is_active', true)
            ->first();

        if (!$prompt) {
            $this->showError('Эта задача сейчас недоступна. Выберите другую.');
            return;
        }

        if (!config('services.openrouter.api_key')) {
            $this->showError('ИИ-помощник пока не настроен. Попробуйте позже.');
            return;
        }

        $attemptType = $this->consumeAttempt();

        if ($attemptType === null) {
            $this->showError('Бесплатные и купленные попытки закончились. Купите пакет дополнительных попыток, чтобы продолжить.');
            return;
        }

        try {
            $userPrompt = str_contains($prompt->user_prompt_template, '{{input}}')
                ? str_replace('{{input}}', $validated['text'], $prompt->user_prompt_template)
                : $prompt->user_prompt_template . "\n\n" . $validated['text'];

            $generation = $generator->generateWithUsage($prompt->system_prompt, $userPrompt);
            AiAssistantConversation::create([
                'user_id' => Auth::id(),
                'ai_prompt_id' => $prompt->id,
                'prompt_title' => $prompt->title,
                'question' => $validated['text'],
                'answer' => $generation->text,
                'model' => config('services.openrouter.model'),
                'openrouter_cost_usd' => $generation->costUsd,
                'openrouter_generation_id' => $generation->generationId,
            ]);
            $this->result = $generation->text;
            $this->resetPage();
        } catch (\Throwable $exception) {
            if ($attemptType === 'paid') {
                DB::table('users')->where('id', Auth::id())->increment('ai_attempts_purchased');
            }
            report($exception);
            $message = $exception instanceof \RuntimeException
                ? $exception->getMessage()
                : 'Не удалось обработать запрос. Попробуйте позже.';
            $this->showError($message);
        }
    }

    public function generateImage(AiImageGenerator $generator): void
    {
        $this->generatedImageUrl = null;
        $this->resetValidation();

        try {
            $validated = $this->validate([
                'imagePrompt' => ['required', 'string', 'min:5', 'max:5000'],
            ], [
                'imagePrompt.required' => 'Опишите изображение, которое нужно создать.',
                'imagePrompt.min' => 'Добавьте немного больше деталей для генерации.',
                'imagePrompt.max' => 'Описание должно быть не длиннее 5 000 символов.',
            ]);
        } catch (ValidationException $exception) {
            $this->resetValidation();
            $this->showError(collect($exception->errors())->flatten()->implode("\n"));
            return;
        }

        if (!config('services.openrouter.api_key')) {
            $this->showError('ИИ-помощник пока не настроен. Попробуйте позже.');
            return;
        }

        $attemptType = $this->consumeAttempt();
        if ($attemptType === null) {
            $this->showError('Бесплатные и купленные попытки закончились. Купите пакет дополнительных попыток, чтобы продолжить.');
            return;
        }

        $imagePath = null;
        try {
            $generation = $generator->generate($validated['imagePrompt']);
            $imagePath = 'ai-generated/'.Auth::id().'/'.$generation->fileName;
            if (!Storage::disk('public')->put($imagePath, $generation->imageBytes)) {
                throw new \RuntimeException('Не удалось сохранить изображение. Попробуйте позже.');
            }

            AiAssistantConversation::create([
                'user_id' => Auth::id(),
                'prompt_title' => 'Генерация изображения',
                'question' => $validated['imagePrompt'],
                'answer' => 'Изображение создано.',
                'model' => config('services.openrouter.image_model'),
                'openrouter_cost_usd' => $generation->costUsd,
                'openrouter_generation_id' => $generation->generationId,
                'generation_type' => 'image',
                'image_path' => $imagePath,
            ]);

            $this->generatedImageUrl = Storage::disk('public')->url($imagePath);
            $this->resetPage();
        } catch (\Throwable $exception) {
            if ($imagePath) {
                Storage::disk('public')->delete($imagePath);
            }
            if ($attemptType === 'paid') {
                DB::table('users')->where('id', Auth::id())->increment('ai_attempts_purchased');
            }
            report($exception);
            $message = $exception instanceof \RuntimeException
                ? $exception->getMessage()
                : 'Не удалось создать изображение. Попробуйте позже.';
            $this->showError($message);
        }
    }

    private function consumeAttempt(): ?string
    {
        $limit = max(0, (int) config('services.openrouter.free_attempts', 3));

        return DB::transaction(function () use ($limit): ?string {
            $user = DB::table('users')->where('id', Auth::id())->lockForUpdate()->first();
            if (!$user) return null;

            if ((int) $user->ai_attempts_used < $limit) {
                DB::table('users')->where('id', $user->id)->increment('ai_attempts_used');
                return 'free';
            }

            if ((int) $user->ai_attempts_purchased > 0) {
                DB::table('users')->where('id', $user->id)->decrement('ai_attempts_purchased');
                return 'paid';
            }

            return null;
        });
    }

    public function buyAttempts(PaymentService $paymentService): void
    {
        $user = DB::table('users')->where('id', Auth::id())->first();
        $freeLimit = max(0, (int) config('services.openrouter.free_attempts', 3));
        if (!$user || (int) $user->ai_attempts_used < $freeLimit) {
            $this->showError('Пакет попыток можно купить после использования бесплатных попыток.');
            return;
        }

        $packSize = (int) config('services.openrouter.paid_attempts.pack_size', 10);
        $price = round((float) config('services.openrouter.paid_attempts.pack_price', 0), 2);
        if ($packSize < 1 || $price <= 0) {
            $this->showError('Покупка попыток сейчас недоступна. Попробуйте позже.');
            return;
        }

        if (!config('services.yookassa.shop_id') || !config('services.yookassa.secret_key')) {
            $this->showError('Оплата сейчас недоступна. Попробуйте позже.');
            return;
        }

        try {
            $paymentUrl = $paymentService->createPayment(
                amount: $price,
                urlRedirect: route('account.ai-assistant', ['payment' => 'ai_attempt_pack']),
                transactionData: [
                    'type' => TransactionTypeEnums::AI_ATTEMPT_PACK_PURCHASE->value,
                    'description' => "Покупка {$packSize} попыток ИИ-помощника",
                    'data' => ['attempts' => $packSize],
                ],
            );

            $this->redirect($paymentUrl);
        } catch (\Throwable $exception) {
            report($exception);
            $this->showError('Не удалось создать платёж. Попробуйте позже.');
        }
    }

    private function showError(string $message): void
    {
        $this->dispatch('swal', type: 'error', title: 'Ошибка', text: $message);
    }

    public function render()
    {
        $user = DB::table('users')->where('id', Auth::id())->first();
        $used = (int) ($user->ai_attempts_used ?? 0);
        $purchasedAttempts = (int) ($user->ai_attempts_purchased ?? 0);
        $limit = max(0, (int) config('services.openrouter.free_attempts', 3));
        $packSize = (int) config('services.openrouter.paid_attempts.pack_size', 10);
        $packPrice = round((float) config('services.openrouter.paid_attempts.pack_price', 0), 2);
        $prompts = AiPrompt::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get(['id', 'title', 'description', 'input_label', 'input_placeholder']);

        return view('livewire.pages.account.ai.ai-assistant-page', [
            'freeAttemptsRemaining' => max(0, $limit - $used),
            'purchasedAttempts' => $purchasedAttempts,
            'attemptsRemaining' => max(0, $limit - $used) + $purchasedAttempts,
            'attemptLimit' => $limit,
            'attemptPackSize' => $packSize,
            'attemptPackPrice' => $packPrice,
            'canBuyAttempts' => max(0, $limit - $used) === 0
                && $packSize > 0
                && $packPrice > 0
                && (bool) config('services.yookassa.shop_id')
                && (bool) config('services.yookassa.secret_key'),
            'prompts' => $prompts,
            'selectedPrompt' => $prompts->firstWhere('id', $this->selectedPromptId),
            'history' => AiAssistantConversation::query()
                ->where('user_id', Auth::id())
                ->latest()
                ->paginate(10),
        ])->layout('layouts.account');
    }
}
