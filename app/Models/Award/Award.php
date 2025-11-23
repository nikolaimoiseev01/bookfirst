<?php

namespace App\Models\Award;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Award extends Model
{
    public function awardType(): BelongsTo {
        return $this->belongsTo(AwardType::class);
    }
}
