<?php

namespace App\Models\OwnBook;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class OwnBook extends Model implements HasMedia
{
    use InteractsWithMedia;
    //
}
