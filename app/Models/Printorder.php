<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Printorder extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'collection_id',
        'own_book_id',
        'participation_id',
        'books_needed',
        'cover_type',
        'inside_color',
        'color_pages',
        'send_to_name',
        'send_to_tel',
        'send_to_country',
        'send_to_city',
        'send_to_address',
        'send_to_index',
        'address',
        'address_country'
    ];

    public function Participation()
    {
        return $this->belongsTo(Participation::class);
    }


}
