<?php

namespace App\Models;

use App\Models\User\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class PromocodeStat extends Model
{
    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }

    public function promocode(): BelongsTo {
        return $this->belongsTo(Promocode::class);
    }

    public function model(): MorphTo
    {
        return $this->morphTo();
    }
}
