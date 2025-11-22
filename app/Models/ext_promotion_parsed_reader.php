<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ext_promotion_parsed_reader extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'ext_promotion_id',
        'checked_at',
        'readers_num'
    ];


    public function User() {
        return $this->belongsTo(User::class);
    }

    public function ext_promotion() {
        return $this->belongsTo(ext_promotion::class);
    }
}
