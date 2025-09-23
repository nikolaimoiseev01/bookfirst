<?php

namespace App\Models\Collection;

use App\Models\Chat\Chat;
use App\Models\PrintOrder\PrintOrder;
use App\Models\Promocode;
use App\Models\Survey\SurveyCompleted;
use App\Models\User\User;
use App\Models\Work\Work;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Participation extends Model
{

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function participationStatus()
    {
        return $this->belongsTo(ParticipationStatus::class);
    }

    public function collection()
    {
        return $this->belongsTo(Collection::class);
    }

    public function printOrder() {
        return $this->belongsTo(PrintOrder::class);
    }

    public function surveyCompleted() {
        return $this->morphOne(SurveyCompleted::class, 'model');
    }

    public function promocode() {
        return $this->belongsTo(Promocode::class);
    }

    public function chat(): MorphOne
    {
        return $this->morphOne(Chat::class, 'model');
    }

    public function works()
    {
        return $this->hasMany(Work::class);
    }
}
