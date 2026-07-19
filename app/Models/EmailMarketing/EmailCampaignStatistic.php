<?php

namespace App\Models\EmailMarketing;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $email_campaign_id
 * @property int $total
 * @property int $send_ok
 * @property int $send_fail
 * @property int $open_msg
 * @property int $open_msg_uniq
 * @property int $click_link
 * @property int $click_link_uniq
 * @property int $gen_ok
 * @property int $dup
 * @property int $bad
 * @property int $fbl
 * @property int $stop
 * @property int $trap
 * @property int $bounce
 * @property int $spam
 * @property int $unsubscribe
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
class EmailCampaignStatistic extends Model
{
    use HasFactory;

    protected $fillable = [
        'email_campaign_id',
        'total',
        'send_ok',
        'send_fail',
        'open_msg',
        'open_msg_uniq',
        'click_link',
        'click_link_uniq',
        'gen_ok',
        'dup',
        'bad',
        'fbl',
        'stop',
        'trap',
        'bounce',
        'spam',
        'unsubscribe',
    ];

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(EmailCampaign::class);
    }
}
