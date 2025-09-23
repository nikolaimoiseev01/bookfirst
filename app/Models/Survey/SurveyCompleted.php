<?php

namespace App\Models\Survey;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class SurveyCompleted extends Model
{
    public function model(): MorphTo
    {
        return $this->morphTo();
    }
}
