<?php

namespace App\Models\Collection;

use App\Models\Work\Work;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Collection extends Model implements HasMedia
{
    use InteractsWithMedia;

    public const MORPH_ALIAS = 'Collection';

    public function CollectionStatus()
    {
        return $this->belongsTo(CollectionStatus::class);
}

    public function Participations()
    {
        return $this->hasMany(Participation::class);
    }

    public function participationWorks()
    {
        return $this->hasManyThrough(ParticipationWork::class, Participation::class);
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

}
