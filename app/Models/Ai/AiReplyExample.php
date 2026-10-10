<?php

namespace App\Models\Ai;

use Illuminate\Database\Eloquent\Model;

class AiReplyExample extends Model
{
    protected $fillable = ['customer_question', 'approved_answer', 'chat_id', 'message_id', 'approved_by', 'is_active'];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }
}
