<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class message_file extends Model
{
    use HasFactory;

    public function Message() {
        return $this->belongsTo(Message::class);
    }
}
