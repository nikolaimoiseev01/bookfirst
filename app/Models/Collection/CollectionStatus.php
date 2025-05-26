<?php

namespace App\Models\Collection;

use Illuminate\Database\Eloquent\Model;

class CollectionStatus extends Model
{
    public function Collection()
    {
        return $this->hasMany(Collection::class);
    }
}
