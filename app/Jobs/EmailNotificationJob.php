<?php

namespace App\Jobs;

use App\Models\User\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Notifications\Notification;

class EmailNotificationJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    private int $userId;
    private Notification $notification;
    public function __construct(int $userId, Notification $notification)
    {
        $this->notification = $notification;
        $this->userId = $userId;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $user = User::where('id', $this->userId)->first();
        $user->notify($this->notification);

    }
}
