<?php

namespace App\Models\Work;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Work extends Model
{
    public function workLikes(): hasMany {
        return $this->hasMany(WorkLike::class);
    }
}
