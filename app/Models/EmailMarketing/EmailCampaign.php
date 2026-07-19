<?php

namespace App\Models\EmailMarketing;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $name
 * @property string $subject
 * @property int $email_recipient_list_id
 * @property int|null $email_template_id
 * @property int|null $created_by
 * @property \Illuminate\Support\Carbon|null $scheduled_at
 * @property \Illuminate\Support\Carbon|null $sent_at
 * @property string|null $html_content
 * @property int|null $mailganer_id
 * @property string $status
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
class EmailCampaign extends Model
{

    protected $fillable = [
        'name',
        'subject',
        'scheduled_at',
        'sent_at',
        'status',
        'email_recipient_list_id',
        'email_template_id',
        'created_by',
        'html_content',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'sent_at' => 'datetime',
    ];

    public function recipientList(): BelongsTo
    {
        return $this->belongsTo(EmailRecipientList::class, 'email_recipient_list_id');
    }

    public function emailTemplate(): BelongsTo
    {
        return $this->belongsTo(EmailTemplate::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User\User::class, 'created_by');
    }

    public function campaignRecipients(): HasMany
    {
        return $this->hasMany(EmailCampaignRecipient::class);
    }

    public function statistic(): HasMany
    {
        return $this->hasMany(EmailCampaignStatistic::class);
    }
}
