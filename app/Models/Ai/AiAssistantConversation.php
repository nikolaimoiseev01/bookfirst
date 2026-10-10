<?php

namespace App\Models\Ai;

use App\Models\User\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AiAssistantConversation extends Model
{
    protected $fillable = [
        'user_id',
        'ai_prompt_id',
        'prompt_title',
        'question',
        'answer',
        'model',
        'openrouter_cost_usd',
        'openrouter_generation_id',
        'generation_type',
        'image_path',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function prompt(): BelongsTo
    {
        return $this->belongsTo(AiPrompt::class, 'ai_prompt_id');
    }
}
