<?php

namespace App\Jobs;

use App\Models\User\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Notifications\Notification;

class TelegramNotificationJob implements ShouldQueue
{
    use Queueable;

    private Notification $notification;
    private $chat;

    public function __construct(Notification $notification, $chat=null)
    {
        $this->notification = $notification;
        $this->chat = $chat;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        \Illuminate\Support\Facades\Notification::route('telegram', getTelegramChatId($this->chat))
            ->notify($this->notification);

    }


}
