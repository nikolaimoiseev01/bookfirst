<?php

namespace App\Models\OwnBook;

use App\Models\Chat\Chat;
use App\Models\Collection\CollectionStatus;
use App\Models\PreviewComment;
use App\Models\PrintOrder\PrintOrder;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Model;
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

    public function ownBookStatus()
    {
        return $this->belongsTo(OwnBookStatus::class);
    }
    public function ownBookInsideStatus()
    {
        return $this->belongsTo(OwnBookInsideStatus::class);
    }
    public function ownBookCoverStatus()
    {
        return $this->belongsTo(OwnBookCoverStatus::class);
    }
    public function printOrders(): MorphMany
    {
        return $this->morphMany(PrintOrder::class, 'model');
    }

    public function firstPrintOrder()
    {
        $firstOrder = $this->printOrders()
            ->orderBy('created_at', 'asc')
            ->first();
         return $firstOrder;
    }

    public function previewComments(): MorphMany
    {
        return $this->morphMany(PreviewComment::class, 'model');
    }

    public function chat(): MorphOne
    {
        return $this->morphOne(Chat::class, 'model');
    }


    protected $casts = [
        'selling_links' => 'array'
    ];
}
