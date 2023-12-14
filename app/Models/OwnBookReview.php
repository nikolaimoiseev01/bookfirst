<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OwnBookReview extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'own_book_id',
        'stars',
        'text'
    ];

    public function own_book()
    {
        return $this->belongsTo(own_book::class);
    }

    public function user()
    {
        return $this->belongsTo(user::class);
    }

}
