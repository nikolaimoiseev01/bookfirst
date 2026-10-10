<?php

namespace App\Models\Ai;

use Illuminate\Database\Eloquent\Model;

class AiPrompt extends Model
{
    protected $fillable = [
        'title',
        'description',
        'input_label',
        'input_placeholder',
        'system_prompt',
        'user_prompt_template',
        'sort_order',
        'is_active',
    ];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }
}
