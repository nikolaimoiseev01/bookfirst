<?php

namespace App\Models;

use App\Enums\AlmostCompleteActionTypeEnums;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Model;

class AlmostCompleteAction extends Model
{
    protected $casts = [
        'data' => 'array',
        'type' => AlmostCompleteActionTypeEnums::class
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
