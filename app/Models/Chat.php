<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    use HasFactory;

    protected $fillable = [
        'chat_status_id',
        'flg_chat_read'
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

    public function ext_promotion()
    {
        return $this->belongsTo(ext_promotion::class);
    }
}
