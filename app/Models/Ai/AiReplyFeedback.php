<?php

namespace App\Models\Ai;

use Illuminate\Database\Eloquent\Model;

class AiReplyFeedback extends Model
{
    protected $fillable = ['chat_id', 'admin_user_id', 'draft', 'final_answer', 'outcome', 'source_titles'];

    protected function casts(): array
    {
        return ['source_titles' => 'array'];
    }
}
