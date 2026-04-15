<?php

namespace App\Console\Commands;

use App\Enums\ExtPromotionStatusEnums;
use App\Filament\Resources\ExtPromotions\Pages\ListExtPromotions;
use App\Jobs\TelegramNotificationJob;
use App\Models\ExtPromotion\ExtPromotion;
use App\Notifications\TelegramDefaultNotification;
use App\Services\ExtPromotionStatUpdateService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;

class ExtPromotionStatUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:ext-promotion-stat-update';

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
        $extPromotions = ExtPromotion::where('status', ExtPromotionStatusEnums::IN_PROGRESS)->get();
        foreach ($extPromotions as $extPromotion) {
            (new ExtPromotionStatUpdateService($extPromotion))->addNewStat();
        }
        $count = count($extPromotions);
        $subject = "📊 Сохранили статистику по продвижениям!";
        $text = "Обработали человек: {$count}";
        $url = null;
        TelegramNotificationJob::dispatch(new TelegramDefaultNotification($subject, $text, $url, 'extPromotion'));
    }
}
