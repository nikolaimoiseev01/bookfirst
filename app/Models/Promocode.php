<?php

namespace App\Models;

use App\Models\User\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Promocode extends Model
{
    public const TYPE_SYSTEM = 'Системные';
    public const TYPE_FRIEND_INVITE = 'Приведи друга';

    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }

    public function stats(): HasMany {
        return $this->hasMany(PromocodeStat::class);
    }
}
