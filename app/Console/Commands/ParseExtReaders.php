<?php

namespace App\Console\Commands;

use App\Models\ext_promotion;
use App\Models\ext_promotion_parsed_reader;
use App\Notifications\TelegramNotification;
use App\Service\ExtPromotionStatUpdateService;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;

class ParseExtReaders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ParseExtReaders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */


    public function handle(ExtPromotionStatUpdateService $stat)
    {

        $ext_promotions = ext_promotion::where('ext_promotion_status_id', 4)->get();
        $check_cnt = 0;

        foreach ($ext_promotions as $ext_promotion) {
            if($stat->check_max($ext_promotion)) {
                $check_cnt += 1;
                $stat->add_new_time($ext_promotion);
            }
        }

        if($check_cnt > 0) {
            // Посылаем Telegram уведомление нам
            Notification::route('telegram', ENV('APP_DEBUG') ? "-4176126016" : '-4120321987')
                ->notify(new TelegramNotification('📊 *Сохранили статистику по продвижениям!*',
                    "Обработали человек: *$check_cnt*",
                    null,
                    null));
        }

    }
}
