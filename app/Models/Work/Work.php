<?php

namespace App\Models\Work;

use App\Models\User\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Work extends Model implements HasMedia
{

    use InteractsWithMedia;
    public function workLikes(): hasMany {
        return $this->hasMany(WorkLike::class);
    }

    public function user(): belongsTo {
        return $this->belongsTo(User::class);
    }
}
