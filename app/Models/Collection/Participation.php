<?php

namespace App\Models\Collection;

use App\Enums\ParticipationStatusEnums;
use App\Filament\Resources\Collection\Participations\Pages\EditParticipation;
use App\Models\Chat\Chat;
use App\Models\PreviewComment;
use App\Models\PrintOrder\PrintOrder;
use App\Models\Promocode;
use App\Models\Survey\SurveyCompleted;
use App\Models\Transaction;
use App\Models\User\User;
use App\Models\Work\Work;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Participation extends Model
{

    protected $casts = [
        'status' => ParticipationStatusEnums::class,
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
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

    public function previewComments(): MorphMany
    {
        return $this->morphMany(PreviewComment::class, 'model');
    }

    public function participationWorks(): HasMany
    {
        return $this->hasMany(ParticipationWork::class);
    }

    public function transactions(): morphMany
    {
        return $this->morphMany(Transaction::class,'model');
    }

    public function adminEditPage(): string
    {
        return EditParticipation::getUrl(['record' => $this]);
    }

    public function accountIndexPage(): string
    {
        return route('account.participation.index', $this->id);
    }
}
