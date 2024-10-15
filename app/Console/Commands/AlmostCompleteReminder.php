<?php

namespace App\Console\Commands;

use App\Models\almost_complete_action;
use App\Models\own_book;
use App\Models\Participation;
use App\Notifications\EmailNotification;
use App\Notifications\TelegramNotification;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Notification;

class AlmostCompleteReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'AlmostCompleteReminder';

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

        $actions = almost_complete_action::where('cnt_email_sent', '<', 4)->get();

        $cnt_authors = 0;

        foreach ($actions as $action) {
            $user = $action->user;

            $already_participate = Participation::where('user_id', $user['id'])->where('collection_id', $action['collection_id'])->first();
            $already_has_book = own_book::where('user_id', $user['id'])->first();

            if (!($already_participate ?? null) and $action['almost_complete_action_type_id'] == 1) {
                $cnt_authors += 1;
                $email_text = "Мы заметили, что вы начали заполнять заявку на участие в сборнике '{$action->collection['title']}', но не закончили.
                                    Нам понравились ваши произведения, и мы хотим предоставить вам промокод на скидку в 30% на участие в этом сборнике: ALMOST_30 <br>
                                    Продолжить заполнение вы можете по кнопке ниже: ";
                $button_link = route('participation_create', $action['collection_id']);
            } elseif (!($already_has_book ?? null) and $action['almost_complete_action_type_id'] == 2) { /* Это напоминание про заявку собственной книги */
                $email_text = "Мы заметили, что вы начали заполнять заявку на издание книги, но не закончили.
            Продолжить заполнение вы можете по кнопке ниже: ";
                $button_link = route('own_book_create');
            }

            $cnt_emails_pre = $action['cnt_email_sent'];
            $action->update([
                'cnt_email_sent' => $cnt_emails_pre + 1,
                'dt_last_email_sent' => Date::parse(Carbon::now())->addHour(3)
            ]);

            $user->notify(new EmailNotification(
                    'Незаконченное действие!',
                    $user['name'],
                    $email_text,
                    'Продолжить заполнение',
                    $button_link)
            );
        }

        if ($cnt_authors > 0) {
            $tel_text = "📧 Напомнили о заявках авторам: *" . $cnt_authors . '*';
            Notification::route('telegram', ENV('APP_DEBUG') ? "-4176126016" : '-506622812')
                ->notify(new TelegramNotification($tel_text,
                    "",
                    null,
                    null));
        }


    }
}
