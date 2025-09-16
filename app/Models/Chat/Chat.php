<?php

namespace App\Models\Chat;

use App\Models\Collection\Participation;
use App\Models\ExtPromotion\ExtPromotion;
use App\Models\OwnBook\OwnBook;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Chat extends Model
{

    public function model(): MorphTo
    {
        return $this->morphTo();
    }

    // Вернёт модель, если это Participation, иначе null
    public function getParticipationAttribute(): ?Participation
    {
        return $this->model instanceof Participation ? $this->model : null;
    }

    // Вернёт модель, если это OwnBook, иначе null
    public function getOwnBookAttribute(): ?OwnBook
    {
        return $this->model instanceof OwnBook ? $this->model : null;
    }

    public function getExtPromotionAttribute(): ?ExtPromotion
    {
        return $this->model instanceof ExtPromotion ? $this->model : null;
    }


    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }

    public function chatStatus(): BelongsTo
    {
        return $this->belongsTo(ChatStatus::class);
    }
}
