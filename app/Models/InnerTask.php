<?php

namespace App\Models;

use App\Enums\InnerTaskTypeEnums;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class InnerTask extends Model
{
    protected $casts = [
        'type' => InnerTaskTypeEnums::class
    ];

    protected static function booted(): void
    {
        // Выполненные кастомные задачи не учитываются в списках, счётчиках и рассылках.
        static::addGlobalScope('excludeFinishedCustom', function (Builder $builder) {
            $builder->where(fn (Builder $query) => $query
                ->whereNull('flg_custom_finished')
                ->orWhere('flg_custom_finished', false));
        });
    }

    public function model(): MorphTo
    {
        return $this->morphTo();
    }
}
