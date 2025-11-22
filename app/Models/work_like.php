<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class work_like extends Model
{
    use HasFactory;

    public function work() {
        return $this->belongsTo(Work::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }
}
