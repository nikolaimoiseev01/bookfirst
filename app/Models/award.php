<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class award extends Model
{
    use HasFactory;

    public function award_type() {
        return $this->belongsTo(award_type::class);
    }
}
