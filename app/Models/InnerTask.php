<?php

namespace App\Models;

use App\Enums\InnerTaskTypeEnums;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class InnerTask extends Model
{
    protected $casts = [
        'type' => InnerTaskTypeEnums::class
    ];
    public function model(): MorphTo
    {
        return $this->morphTo();
    }
}
