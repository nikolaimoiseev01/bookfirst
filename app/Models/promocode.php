<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class promocode extends Model
{

    protected $fillable = [
        'promocode',
        'discount',
    ];

    use HasFactory;
}
