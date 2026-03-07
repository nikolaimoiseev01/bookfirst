<?php

namespace App\Models\PrintOrder;

use App\Enums\PrintOrderStatusEnums;
use App\Filament\Resources\PrintOrder\PrintOrders\Pages\EditPrintOrder;
use App\Models\Chat\Chat;
use App\Models\ExtPromotion\ExtPromotionParsedReader;
use App\Models\Survey\SurveyCompleted;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
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

    public function adminEditPageWithoutLogin(): string
    {
        return EditPrintOrder::getUrl(['record' => $this]);
    }

    public function chat(): MorphOne
    {
        return $this->morphOne(Chat::class, 'model');
    }

    public function surveyCompleted() {
        return $this->morphOne(SurveyCompleted::class, 'model');
    }

    public function accountIndexPage(): string
    {
        return route('account.purchase-print.index', $this->id);
    }

    protected $casts = [
        'address_json' => 'array',
        'status' => PrintOrderStatusEnums::class,
    ];
}
