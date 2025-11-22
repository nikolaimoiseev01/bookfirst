<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InnerTask extends Model
{
    use HasFactory;

    protected $fillable = [
        'collection_id',
        'own_book_id',
        'responsible',
        'title',
        'description',
        'deadline',
        'deadline_inner',
        'inner_task_status_id',
        'inner_task_type_id'
    ];

    public function Collection() {
        return $this->belongsTo(Collection::class);
    }

    public function own_book() {
        return $this->belongsTo(own_book::class);
    }

    public function InnerTaskStatus() {
        return $this->belongsTo(InnerTaskStatus::class);
    }

    public function InnerTaskType() {
        return $this->belongsTo(InnerTaskType::class);
    }
}
