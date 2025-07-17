<?php

namespace App\Models\AlmostCompleteAction;

use Illuminate\Database\Eloquent\Model;

class AlmostCompleteAction extends Model
{
    public function AlmostCompleteActionType()
    {
        return $this->belongsTo(AlmostCompleteActionType::class);
    }
}
