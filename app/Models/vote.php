<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class vote extends Model
{
    use HasFactory;

    public function Participation() {
        return $this->belongsTo(Participation::class);
    }
}
