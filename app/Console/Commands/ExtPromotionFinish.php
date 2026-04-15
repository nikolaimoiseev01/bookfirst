<?php

namespace App\Console\Commands;

use App\Enums\ExtPromotionStatusEnums;
use App\Filament\Resources\ExtPromotions\Pages\ListExtPromotions;
use App\Jobs\TelegramNotificationJob;
use App\Models\ExtPromotion\ExtPromotion;
use App\Notifications\TelegramDefaultNotification;
use App\Services\ExtPromotionStatUpdateService;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;

class ExtPromotionFinish extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:ext-promotion-finish';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $extPromotions = ExtPromotion::where('status', ExtPromotionStatusEnums::IN_PROGRESS)
            ->get();
        $updatedCnt = 0;
        foreach ($extPromotions as $extPromotion) {
            $endDate = formatDate($extPromotion['started_at'], 'j F', $extPromotion['days']);
            if (formatDate(Carbon::now()) == $endDate) {
                $extPromotion->update([
                    'status' => ExtPromotionStatusEnums::DONE
                ]);
                $updatedCnt += 1;
            }
        }
        if ($updatedCnt > 0) {
            $subject = "📊 Закончили продвижение для авторов: {$updatedCnt}";
            $url = route('login_as_secondary_admin', ['url_redirect' => ListExtPromotions::getUrl()]);
            TelegramNotificationJob::dispatch(new TelegramDefaultNotification($subject, "", $url, 'extPromotion'));
        }
    }
}
