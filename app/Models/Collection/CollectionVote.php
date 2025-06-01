<?php

namespace App\Models\Collection;

use App\Models\User\User;
use Illuminate\Database\Eloquent\Model;

class CollectionVote extends Model
{
    public function participation_from()
    {
        return $this->belongsTo(Participation::class, 'participation_id_from');
    }

    public function participation_to()
    {
        return $this->belongsTo(Participation::class, 'participation_id_to');
    }
}
