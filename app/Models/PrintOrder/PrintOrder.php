<?php

namespace App\Models\PrintOrder;

use App\Enums\PrintOrderStatusEnums;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class PrintOrder extends Model
{
    public function model(): MorphTo
    {
        return $this->morphTo();
    }

    public function user(): belongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function printingCompany(): belongsTo
    {
        return $this->belongsTo(PrintingCompany::class);
    }

    public function logisticCompany(): belongsTo
    {
        return $this->belongsTo(LogisticCompany::class);
    }

    public function printOrderStatus(): belongsTo
    {
        return $this->belongsTo(PrintOrderStatus::class);
    }

    public function trackingLink(): string
    {
        return $this->logisticCompany['base_tracking_link'] . $this->track_number;
    }

    protected $casts = [
        'address_json' => 'array',
        'status' => PrintOrderStatusEnums::class,
    ];
}
