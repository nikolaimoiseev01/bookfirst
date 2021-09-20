<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class own_books_works extends Model
{
    use HasFactory;

    public function Work() {
        return $this->belongsTo(Work::class);
    }
}
