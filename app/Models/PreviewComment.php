<?php

namespace App\Models;

use App\Models\Collection\Participation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class PreviewComment extends Model
{
    public function model(): MorphTo
    {
        return $this->morphTo();
    }

    public function getParticipationAttribute(): ?Participation
    {
        return $this->model instanceof Participation ? $this->model : null;
    }
}
