<?php

namespace App\Console\Commands;

use App\Services\InnerTasksService;
use Illuminate\Console\Command;

class InnerTasksNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:internal-tasks-notification';

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
        (new InnerTasksService())->update();
    }
}
