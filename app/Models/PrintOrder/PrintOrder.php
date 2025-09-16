<?php

namespace App\Models\PrintOrder;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class PrintOrder extends Model
{
    public function model(): MorphTo
    {
        return $this->morphTo();
    }
}
