<?php

namespace App\Models\Survey;

use App\Models\User\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class SurveyCompleted extends Model
{
    public function model(): MorphTo
    {
        return $this->morphTo();
    }

    public function user(): belongsTo {
        return $this->belongsTo(User::class);
    }

    public function surveyAnswers(): hasMany {
        return $this->hasMany(SurveyAnswer::class);
    }
}
