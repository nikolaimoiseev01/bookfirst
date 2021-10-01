<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Collection extends Model
{
    use HasFactory;

    public function col_status() {
            return $this->belongsTo(Col_status::class);
    }

    public function digital_sale() {
        return $this->belongsTo(digital_sale::class, 'id', 'bought_collection_id');
    }
}
