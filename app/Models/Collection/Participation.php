<?php

namespace App\Models\Collection;

use Illuminate\Database\Eloquent\Model;

class Participation extends Model
{
    public function Collection()
    {
        return $this->belongsTo(Collection::class);
    }

    public function Works()
    {
        return $this->hasMany(Work::class);
    }
}
