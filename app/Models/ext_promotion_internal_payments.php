<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ext_promotion_internal_payments extends Model
{
    use HasFactory;

    protected $fillable = [
        'paid_for',
        'amount'
    ];
}
