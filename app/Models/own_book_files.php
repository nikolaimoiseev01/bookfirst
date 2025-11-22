<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class own_book_files extends Model
{
   use HasFactory;

    public function own_book() {
        return $this->belongsTo(own_book::class);
    }
}
