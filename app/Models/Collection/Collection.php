<?php

namespace App\Models\Collection;

use App\Models\PreviewComment;
use App\Models\Work\Work;
use App\Models\Work\WorkType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Collection extends Model implements HasMedia
{
    use InteractsWithMedia;

    public const MORPH_ALIAS = 'Collection';

    public function collectionStatus()
    {
        return $this->belongsTo(CollectionStatus::class);
    }
    public function workType()
    {
        return $this->belongsTo(WorkType::class);
    }

    public function participations()
    {
        return $this->hasMany(Participation::class);
    }

    public function collectionVotes()
    {
        return $this->hasMany(CollectionVote::class);
    }

    public function participationWorks()
    {
        return $this->hasManyThrough(ParticipationWork::class, Participation::class);
    }

    public function previewComments(): MorphMany
    {
        return $this->morphMany(PreviewComment::class, 'model');
    }



//    public function Printorder() {
//        return $this->hasMany(Printorder::class);
//    }
//
//    public function preview_comment() {
//        return $this->hasMany(preview_comment::class);
//    }
//
//    public function digital_sale() {
//        return $this->belongsTo(digital_sale::class, 'id', 'bought_collection_id');
//    }

    protected $casts = [
        'winner_participations' => 'array',
        'selling_links' => 'array',
    ];
}
