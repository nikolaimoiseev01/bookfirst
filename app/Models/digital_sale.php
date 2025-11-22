<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class digital_sale extends Model
{
    use HasFactory;

    public function User() {
        return $this->belongsTo(User::class);
    }

    public function Collection() {
        return $this->belongsTo(Collection::class, 'bought_collection_id', 'id');
    }


    public function own_book() {
        return $this->belongsTo(own_book::class, 'bought_own_book_id', 'id');
    }
}
