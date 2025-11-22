<?php

namespace App\Models\Collection;

use App\Models\Work\Work;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ParticipationWork extends Model
{
    public function work() {
        return $this->belongsTo(Work::class);
    }

    public function participation(): BelongsTo {
        return $this->belongsTo(Participation::class);
    }
}
