<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Work extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'text',
        'symbols',
        'rows',
        'pages',
        'upload_type',
        'user_id',
        'work_type_id',
        'work_topic_id',
        'picture',
        'picture_cropped'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function work_type() {
        return $this->belongsTo(work_type::class);
    }

    public function work_topic() {
        return $this->belongsTo(work_topic::class);
    }

    public function work_like() {
        return $this->hasMany(work_like::class);
    }

    public function work_comment() {
        return $this->hasMany(work_comment::class);
    }
}
