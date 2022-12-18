<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    public function message_file() {
        return $this->hasMany(message_file::class);
    }

    public function Chat() {
        return $this->belongsTo(Chat::class);
    }
}
