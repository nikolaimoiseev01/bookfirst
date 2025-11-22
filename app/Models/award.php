<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class award extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'award_type_id',
        'collection_id',
        'own_book_id'
    ];


    public function award_type() {
        return $this->belongsTo(award_type::class);
    }
}
