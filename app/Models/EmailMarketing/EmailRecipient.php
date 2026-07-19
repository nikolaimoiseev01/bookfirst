<?php

namespace App\Models\EmailMarketing;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $email_recipient_list_id
 * @property string $email
 * @property string|null $name
 * @property array|null $metadata
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
class EmailRecipient extends Model
{

    protected $fillable = [
        'email_recipient_list_id',
        'email',
        'name',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
    ];

    public function recipientList(): BelongsTo
    {
        return $this->belongsTo(EmailRecipientList::class);
    }
}
