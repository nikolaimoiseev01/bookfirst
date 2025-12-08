<?php

namespace App\Models\ExtPromotion;

use App\Enums\ExtPromotionStatusEnums;
use App\Filament\Resources\ExtPromotions\Pages\EditExtPromotion;
use App\Models\Chat\Chat;
use App\Models\Promocode;
use App\Models\Survey\SurveyCompleted;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class ExtPromotion extends Model
{

    protected $casts = [
        'status' => ExtPromotionStatusEnums::class,
    ];
    public function accountIndexPage(): string
    {
        return route('account.ext_promotion.index', $this->id);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function promocode() {
        return $this->belongsTo(Promocode::class);
    }

    public function chat(): MorphOne
    {
        return $this->morphOne(Chat::class, 'model');
    }

    public function parsedReaders(): hasMany
    {
        return $this->hasMany(ExtPromotionParsedReader::class);
    }

    public function surveyCompleted() {
        return $this->morphOne(SurveyCompleted::class, 'model');
    }

    public function adminEditPage(): string
    {
        return route('login_as_secondary_admin', ['url_redirect', EditExtPromotion::getUrl(['record' => $this])]);
    }

    public function adminEditPageWithoutLogin(): string
    {
        return EditExtPromotion::getUrl(['record' => $this]);
    }

}
