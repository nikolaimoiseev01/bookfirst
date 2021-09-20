<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class preview_comment extends Model
{
    use HasFactory;

    public function User() {
        return $this->belongsTo(User::class);
    }

    public function Participation() {
        return $this->belongsTo(Participation::class);
    }

}
