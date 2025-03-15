<?php

namespace App\Console\Commands;

use App\Models\Collection;
use App\Models\InnerTask;
use App\Models\New_covers_readiness;
use App\Models\own_book;
use App\Notifications\TelegramNotification;
use App\Service\DangerTasksService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Jenssegers\Date\Date;

class DangerTasks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'DangerTasks';

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
        (new DangerTasksService())->update();
    }
}
