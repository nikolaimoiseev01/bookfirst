<?php

namespace App\Models\OwnBook;

use App\Models\Work\Work;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OwnBookWork extends Model
{
    public function work() {
        return $this->belongsTo(Work::class);
    }

    public function ownBook(): BelongsTo {
        return $this->belongsTo(OwnBook::class);
    }
}
