<?php

namespace App\Models\Collection;

use App\Enums\CollectionStatusEnums;
use App\Enums\ParticipationStatusEnums;
use App\Filament\Resources\Collection\Collections\Pages\EditCollection;
use App\Filament\Resources\OwnBook\OwnBooks\Pages\EditOwnBook;
use App\Livewire\Components\Account\PreviewComments;
use App\Models\PreviewComment;
use App\Models\PrintOrder\PrintOrder;
use App\Models\Work\Work;
use App\Models\Work\WorkType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Staudenmeir\EloquentJsonRelations\HasJsonRelationships;
use Staudenmeir\EloquentJsonRelations\Relations\BelongsToJson;
use Staudenmeir\EloquentJsonRelations\Relations\HasManyJson;

class Collection extends Model implements HasMedia
{
    use InteractsWithMedia, HasJsonRelationships;

    public const MORPH_ALIAS = 'Collection';


    protected $casts = [
        'winner_participations' => 'array',
        'selling_links' => 'array',
        'status' => CollectionStatusEnums::class,
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'date_apps_end' => 'date',
        'date_preview_star' => 'date',
        'date_preview_end' => 'date',
        'date_print_start' => 'date',
        'date_print_end' => 'date'
    ];

    public function workType()
    {
        return $this->belongsTo(WorkType::class);
    }

    public function participations(): hasMany
    {
        return $this->hasMany(Participation::class);
    }

    public function approvedParticipations()
    {
        return $this->participations()->where('status', ParticipationStatusEnums::APPROVED);
    }

    public function collectionVotes()
    {
        return $this->hasMany(CollectionVote::class);
    }

    public function participationWorks()
    {
        return $this->hasManyThrough(ParticipationWork::class, Participation::class);
    }

    public function previewComments()
    {
        $result = $this->hasManyThrough(
            PreviewComment::class,
            Participation::class,
            'collection_id',   // Foreign key on participations table
            'model_id',        // Foreign key on preview_comments table
            'id',              // Local key on collections table
            'id'               // Local key on participations table
        )->where('model_type', 'Participation');
        return $result;
    }

    public function winnerParticipations(): belongsToJson
    {
        return $this->belongsToJson(Participation::class, 'winner_participations', 'id');
    }

    public function printOrders(): morphMany
    {
        return $this->morphMany(PrintOrder::class, 'model');
    }

    public function adminEditPage(): string
    {
        return EditCollection::getUrl(['record' => $this]);
    }
//    public function digital_sale() {
//        return $this->belongsTo(digital_sale::class, 'id', 'bought_collection_id');
//    }
}
