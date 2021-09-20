<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class own_book extends Model
{
    use HasFactory;

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function own_book_status() {
        return $this->belongsTo(own_book_status::class);
    }

    public function own_book_inside_status() {
        return $this->belongsTo(own_book_inside_status::class);
    }

    public function own_book_cover_status() {
        return $this->belongsTo(own_book_cover_status::class);
    }

    public function own_books_works() {
        return $this->hasMany(own_books_works::class);
    }

    public function own_book_files() {
        return $this->hasMany(own_book_files::class);
    }

    public function printorder() {
        return $this->hasOne(printorder::class);
    }

}
