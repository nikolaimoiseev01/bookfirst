<?php

namespace App\Models\EmailMarketing;

use App\Models\Promocode;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $name
 * @property string $utm_campaign
 * @property int $promocode_id
 * @property string|null $description
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
class EmailRecipientList extends Model
{

    protected $fillable = [
        'name',
        'description',
    ];

    public function recipients(): HasMany
    {
        return $this->hasMany(EmailRecipient::class);
    }

    public function campaigns(): HasMany
    {
        return $this->hasMany(EmailCampaign::class);
    }

    public function promocode(): BelongsTo
    {
        return $this->belongsTo(Promocode::class);
    }
}
