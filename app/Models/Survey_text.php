<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Survey_text extends Model
{
    use HasFactory;

    protected $fillable = [
        'survey_id',
        'step',
        'stars',
        'question',
        'text'
    ];

    public function survey() {
        return $this->belongsTo(Survey::class);
    }
}
