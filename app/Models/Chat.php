<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    use HasFactory;

    protected $fillable = [
        'chat_status_id',
    ];


    public function Message()
    {
        return $this->hasMany(Message::class);
    }

    public function User()
    {
        return $this->belongsTo(User::class);
    }

    public function chat_status()
    {
        return $this->belongsTo(chat_status::class);
    }
}
