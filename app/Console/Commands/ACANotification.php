<?php

namespace App\Console\Commands;

use App\Enums\AlmostCompleteActionTypeEnums;
use App\Jobs\TelegramNotificationJob;
use App\Models\AlmostCompleteAction;
use App\Models\Collection\Collection;
use App\Notifications\TelegramDefaultNotification;
use Illuminate\Console\Command;

class ACANotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:almost-complete-actions-notification';

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
        $acas = AlmostCompleteAction::query()
            ->where('is_unsubscribed', false)
            ->where('cnt_email_sent', '<', 3)
            ->with('user')
            ->get();
        $stats = [];

        foreach($acas as $aca) {
            $payload = $aca->type->payload($aca);
            $aca->user->notify(
                new \App\Notifications\ACANotification(
                    aca: $aca,
                    text: $payload['email_text'],
                    url: $payload['url'],
                )
            );
            $aca->dt_last_email_sent = now();
            $aca->cnt_email_sent = ($aca->cnt_email_sent ?? 0) + 1;
            $aca->save();

            $type = $aca->type->value;
            $stats[$type] = ($stats[$type] ?? 0) + 1;
        }

        $notificationText = "📊*Незаконченные действия: *\n\n";

        foreach ($stats as $typeValue => $count) {
            $typeEnum = AlmostCompleteActionTypeEnums::from($typeValue);

            $notificationText .= sprintf(
                "%s: %d\n",
                $typeEnum->label(), // или красивый label()
                $count
            );
        }

        $notification = new TelegramDefaultNotification(
            null,
            $notificationText,
            null
        );

        if (!empty($stats)) {
            TelegramNotificationJob::dispatch($notification);
        }

    }

}
