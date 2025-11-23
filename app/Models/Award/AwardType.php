<?php

namespace App\Models\Award;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class AwardType extends Model implements HasMedia
{
    use InteractsWithMedia;

}
