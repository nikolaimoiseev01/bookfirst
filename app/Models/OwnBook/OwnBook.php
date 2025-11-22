<?php

namespace App\Models\OwnBook;

use App\Enums\OwnBookCoverStatusEnums;
use App\Enums\OwnBookInsideStatusEnums;
use App\Enums\OwnBookStatusEnums;
use App\Filament\Resources\OwnBook\OwnBooks\Pages\EditOwnBook;
use App\Models\Chat\Chat;
use App\Models\PreviewComment;
use App\Models\PrintOrder\PrintOrder;
use App\Models\Survey\SurveyCompleted;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class OwnBook extends Model implements HasMedia
{
    use InteractsWithMedia;
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function ownBookStatus(): BelongsTo
    {
        return $this->belongsTo(OwnBookStatus::class);
    }
    public function ownBookInsideStatus(): BelongsTo
    {
        return $this->belongsTo(OwnBookInsideStatus::class);
    }
    public function ownBookCoverStatus(): BelongsTo
    {
        return $this->belongsTo(OwnBookCoverStatus::class);
    }
    public function printOrders(): MorphMany
    {
        return $this->morphMany(PrintOrder::class, 'model');
    }

    public function works(): HasMany
    {
        return $this->hasMany(OwnBookWork::class);
    }

    public function firstPrintOrder()
    {
        $firstOrder = $this->printOrders()
            ->orderBy('created_at', 'asc')
            ->first();
         return $firstOrder;
    }

//    public function previewComments(): MorphMany
//    {
//        return $this->morphMany(PreviewComment::class, 'model');
//    }


    public function previewCommentsCover(): MorphMany
    {
        return $this->morphMany(PreviewComment::class, 'model')->where('comment_type', 'cover');
    }

    public function adminEditPage(): string
    {
        return EditOwnBook::getUrl(['record' => $this]);
    }

    public function accountIndexPage(): string
    {
        return route('account.own_book.index', $this->id);
    }

    public function previewCommentsInside(): MorphMany
    {
        return $this->morphMany(PreviewComment::class, 'model')->where('comment_type', 'inside');
    }

    public function chat(): MorphOne
    {
        return $this->morphOne(Chat::class, 'model');
    }

    public function surveyCompleted() {
        return $this->morphOne(SurveyCompleted::class, 'model');
    }


    protected $casts = [
        'selling_links' => 'array',
        'status_general' => OwnBookStatusEnums::class,
        'status_cover' => OwnBookCoverStatusEnums::class,
        'status_inside' => OwnBookInsideStatusEnums::class,
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deadline_inside' => 'date',
        'deadline_cover' => 'date',
        'deadline_print' => 'date',
        'paid_at_without_print' => 'date',
        'paid_at_print_only' => 'date'
    ];
}
