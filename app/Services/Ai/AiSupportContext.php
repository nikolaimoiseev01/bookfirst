<?php

namespace App\Services\Ai;

use App\Models\Ai\AiKnowledgeDocument;
use App\Models\Ai\AiReplyExample;
use App\Models\Chat\Chat;

class AiSupportContext
{
    public function forChat(Chat $chat): array
    {
        $messages = $chat->messages()->with('user')->latest()->limit(12)->get()->reverse();
        $question = (string) ($messages->reverse()->first(fn ($message) => !$message->user?->hasAnyRole('admin|super_admin|secondary_admin|ext_promotion_admin'))?->text ?? '');
        $keywords = $this->keywords($question);

        $allDocuments = AiKnowledgeDocument::query()->where('is_active', true)->get()
            ->map(fn ($doc) => ['record' => $doc, 'score' => $this->documentScore($keywords, $doc)])
            ->sortByDesc('score')->values();
        $documents = $allDocuments->filter(fn ($item) => $item['score'] > 0)->take(5)->values();
        if ($documents->isEmpty()) $documents = $allDocuments->take(2)->values();

        $allExamples = AiReplyExample::query()->where('is_active', true)->get()
            ->map(fn ($example) => ['record' => $example, 'score' => $this->score($keywords, $example->customer_question.' '.$example->approved_answer)])
            ->sortByDesc('score')->values();
        $examples = $allExamples->filter(fn ($item) => $item['score'] > 0)->take(2)->values();

        $safeMessages = $messages->map(fn ($message) => [
            'author' => $message->user?->hasAnyRole('admin|super_admin|secondary_admin|ext_promotion_admin') ? 'Администратор' : 'Пользователь',
            'text' => mb_substr(strip_tags((string) $message->text), 0, 1800),
        ])->values()->all();

        return [
            'messages' => $safeMessages,
            'case' => $this->caseSummary($chat),
            'documents' => $documents->map(fn ($item) => [
                'title' => $item['record']->title,
                'content' => mb_substr(strip_tags($item['record']->content), 0, 3500),
                'url' => $this->safeUrl($item['record']),
            ])->all(),
            'examples' => $examples->map(fn ($item) => [
                'question' => mb_substr($item['record']->customer_question, 0, 1500),
                'answer' => mb_substr($item['record']->approved_answer, 0, 2500),
            ])->all(),
        ];
    }

    private function caseSummary(Chat $chat): array
    {
        $model = $chat->model;
        if (!$model) return ['type' => 'Общий чат'];

        $result = ['type' => class_basename($model), 'title' => (string) ($chat->title ?? '')];
        foreach (['status', 'created_at', 'updated_at', 'stage', 'payment_status', 'type', 'quantity', 'print_count', 'collection_id'] as $key) {
            $value = $model->getAttribute($key);
            if (is_scalar($value) || $value instanceof \Stringable) $result[$key] = (string) $value;
        }
        return $result;
    }

    private function safeUrl(AiKnowledgeDocument $document): ?string
    {
        if (!$document->route_name || (!str_starts_with($document->route_name, 'portal.') && !str_starts_with($document->route_name, 'social.') && !str_starts_with($document->route_name, 'account.') && !in_array($document->route_name, ['auth.password.request', 'register', 'login'], true)) || !\Illuminate\Support\Facades\Route::has($document->route_name)) return null;
        try { return route($document->route_name, $document->route_parameters ?? []); }
        catch (\Throwable) { return null; }
    }

    private function keywords(string $text): array
    {
        preg_match_all('/[\p{L}\p{N}]{3,}/u', mb_strtolower($text), $matches);
        return array_values(array_unique($matches[0] ?? []));
    }

    private function score(array $keywords, string $text): int
    {
        $text = mb_strtolower($text);
        $score = 0;
        foreach ($keywords as $word) if (mb_strpos($text, $word) !== false) $score++;
        return $score;
    }

    private function documentScore(array $keywords, AiKnowledgeDocument $document): int
    {
        $score = ($this->score($keywords, $document->title) * 4)
            + ($this->score($keywords, (string) $document->category) * 2)
            + $this->score($keywords, $document->content);

        $routeAliases = [
            'portal.own_book.application' => ['книг', 'изда', 'стоим', 'цен', 'калькул', 'рассчит'],
        ];
        foreach ($routeAliases[$document->route_name] ?? [] as $alias) {
            foreach ($keywords as $keyword) {
                if (str_starts_with($keyword, $alias) || str_starts_with($alias, $keyword)) {
                    $score += 8;
                    break;
                }
            }
        }

        return $score;
    }
}
