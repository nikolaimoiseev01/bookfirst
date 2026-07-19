<?php

namespace App\Models\EmailMarketing;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $name
 * @property string $html_content
 * @property string|null $text_content
 * @property array|null $variables
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
class EmailTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'html_content',
        'text_content',
        'variables',
    ];

    protected $casts = [
        'variables' => 'array',
    ];

    public function campaigns(): HasMany
    {
        return $this->hasMany(EmailCampaign::class);
    }
}
