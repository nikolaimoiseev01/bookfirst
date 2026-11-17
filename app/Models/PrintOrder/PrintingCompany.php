<?php

namespace App\Models\PrintOrder;

use Illuminate\Database\Eloquent\Model;

class PrintingCompany extends Model
{
    protected $fillable = [
        'name',
        'pickup_city_code',
        'pickup_delivery_point',
        'pickup_address',
        'pickup_contact_name',
        'pickup_contact_phone',
    ];
}
