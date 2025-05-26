<?php

namespace App\Models\Award;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class AwardType extends Model implements HasMedia
{
    use InteractsWithMedia;
}
