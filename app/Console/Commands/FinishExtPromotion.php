<?php

namespace App\Console\Commands;

use App\Models\ext_promotion;
use App\Notifications\TelegramNotification;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;

class FinishExtPromotion extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'FinishExtPromotion';

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
        $ext_promotion_to_end = ext_promotion::where('ext_promotion_status_id', 4)->get();
        $check_cnt = 0;

        foreach ($ext_promotion_to_end as $ext_promotion) {
            // Предположим, у вас уже есть экземпляр модели
            // 1. Взять поле started_at
            $startedAt = Carbon::parse($ext_promotion['started_at']);

            // 2. Прибавить к нему дни до окончания
            $modifiedDate = $startedAt->addDays($ext_promotion['days']);

            // 3. Truncate до даты
            $modifiedDateTruncated = $modifiedDate->startOfDay(); // Это обнулит время

            // 4. Сравнить текущую дату и то, что получилось после truncate
            $today = Carbon::now()->startOfDay(); // Текущая дата без времени


            // 5. Если совпадает, то выполнить действие
            if ($today->equalTo($modifiedDateTruncated)) {
                $check_cnt += 1;
                $ext_promotion->update([
                    'ext_promotion_status_id' => 9
                ]);
            }

        }


        if ($check_cnt > 0) {
            // Посылаем Telegram уведомление нам
            Notification::route('telegram', '-4120321987')
                ->notify(new TelegramNotification("📊 *Закончили продвижение для {$check_cnt} авторов!*",
                    "",
                    null,
                    null));
        }
    }
}
