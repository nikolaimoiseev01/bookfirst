<?php

namespace App\Console\Commands;

use App\Enums\InnerTaskTypeEnums;
use App\Jobs\TelegramNotificationJob;
use App\Models\InnerTask;
use App\Notifications\TelegramDefaultNotification;
use App\Services\InnerTasksService;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;

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

    private function formatType($type)
    {
        return match ($type) {
            InnerTaskTypeEnums::OWN_BOOK_GENERAL->value => 'Книги',
            InnerTaskTypeEnums::OWN_BOOK_INSIDE->value => 'Макеты',
            InnerTaskTypeEnums::OWN_BOOK_COVER->value => 'Обложки @Kris_Moi',
            InnerTaskTypeEnums::COLLECTION->value => 'Сборники',
            default => ucfirst($type),
        };
    }

    private function formatDeadline($deadline) {
        $date = Carbon::parse($deadline);
        $days = now()->diffInDays($date, false);

        // Выбираем иконку
        $icon = match (true) {
            $days < 0   => '🔥',
            $days <= 3  => '⚠️',
            default     => '',
        };
        $formattedDate = $date->locale('ru')->translatedFormat('j F');
        return "$icon $formattedDate";
    }

    public function handle()
    {
        (new InnerTasksService())->update();

        $tasks = InnerTask::orderBy('type')->orderBy('deadline', 'asc')
            ->get()
            ->groupBy('type'); // сгруппировать по типу

        $output = '';

        foreach ($tasks as $type => $items) {

            // Заголовок секции
            $output .= "*" . $this->formatType($type) . "*\n\n";

            $i = 1;
            foreach ($items as $item) {
                $deadline = $this->formatDeadline($item->deadline);
                $output .= "{$i}. {$item->description}: {$deadline}\n";
                $i++;
            }

            $output .= "\n"; // отступ между секциями
        }

        $notification = new TelegramDefaultNotification("🗓 НАШИ ДЕДЛАЙНЫ 🗓", $output, route('login_as_secondary_admin'));
        TelegramNotificationJob::dispatch($notification);

    }
}
