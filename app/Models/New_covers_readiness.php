<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class New_covers_readiness extends Model
{
    use HasFactory;

    protected $fillable = [
        'flg_ready'
    ];
}
