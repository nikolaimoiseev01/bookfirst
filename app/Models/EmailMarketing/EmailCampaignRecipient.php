<?php

namespace App\Models\EmailMarketing;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $email_campaign_id
 * @property int $email_recipient_id
 * @property string $mailganer_status
 * @property string|null $mailganer_click_link
 * @property string|null $mailganer_reason
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
class EmailCampaignRecipient extends Model
{
    protected $fillable = [
        'email_campaign_id',
        'email_recipient_id',
        'mailganer_status',
        'mailganer_click_link',
        'mailganer_reason',
    ];

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(EmailCampaign::class, 'email_campaign_id');
    }

    public function recipient(): BelongsTo
    {
        return $this->belongsTo(EmailRecipient::class, 'email_recipient_id');
    }
}
