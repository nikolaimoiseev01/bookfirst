<?php

namespace App\Models\Work;

use App\Models\User\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Work extends Model implements HasMedia
{

    use InteractsWithMedia;
    public function likes(): hasMany {
        return $this->hasMany(WorkLike::class);
    }

    public function comments(): hasMany {
        return $this->hasMany(WorkComment::class);
    }

    public function user(): belongsTo {
        return $this->belongsTo(User::class);
    }

    public function workType(): belongsTo {
        return $this->belongsTo(WorkType::class);
    }

    public function workTopic(): belongsTo {
        return $this->belongsTo(WorkTopic::class);
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $conversion = $this->addMediaConversion('thumb')
            ->nonOptimized()
            ->sharpen(10);

        if ($media) {
            // Вытащим расширение файла (png, jpg, webp и т.д.)
            $extension = $media->extension ?? null;

            if ($extension) {
                $conversion->format($extension);
            }
        }
    }
}
