<?php

namespace App\Models\Work;

use App\Models\User\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WorkComment extends Model
{
    public function user(): belongsTo {
        return $this->belongsTo(User::class);
    }
}
