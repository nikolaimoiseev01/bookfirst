<?php

namespace App\Models\Collection;

use App\Models\Work\Work;
use Illuminate\Database\Eloquent\Model;

class ParticipationWork extends Model
{
    public function work() {
        return $this->belongsTo(Work::class);
    }
}
