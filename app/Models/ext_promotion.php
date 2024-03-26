<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ext_promotion extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'ext_promotion_status_id',
        'login',
        'password',
        'site',
        'days',
        'paid_at',
        'price_total',
        'price_executor',
        'price_our',
        'chat_id',
        'promocode_id',
        'started_at'
    ];

    public function User() {
        return $this->belongsTo(User::class);
    }

    public function ext_promotion_status() {
        return $this->belongsTo(ext_promotion_status::class);
    }

    public function Chat() {
        return $this->belongsTo(Chat::class);
    }

    public function promocode() {
        return $this->belongsTo(promocode::class);
    }

    public function ext_promotion_parsed_reader() {
        return $this->hasMany(ext_promotion_parsed_reader::class);
    }

}
