<?php

namespace App\Console\Commands;

use App\Models\Collection;
use App\Models\EmailSent;
use App\Models\Participation;
use App\Models\User;
use App\Notifications\AllParticipantsEmail;
use App\Notifications\EmailNotification;
use App\Notifications\TelegramNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;

class PayReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'PayReminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Напоминание участникам об оплате';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $collections_part_pay = Collection::where('col_status_id', 1)->get();
        $sent_to_users_amount = 0;

        foreach ($collections_part_pay as $collction) {
            $users_from_participation = Participation::where('collection_id', $collction['id'])->where('pat_status_id', 2)->get('user_id')->toArray();
            $users = User::whereIn('id', $users_from_participation)->get();
            $sent_to_users = "";


            foreach ($users as $user) {
                $sent_to_users = $sent_to_users . $user['id'] . ";";
                $sent_to_users_amount += 1;
                $participation = Participation::where('collection_id', $collction['id'])->where('user_id',$user['id'])->first();
                $user->notify(new EmailNotification(
                        'Требуется действие!',
                        $user['name'],
                        'Остался всего один шаг для вступления в ряды авторов сборника "' . $collction['title'] . '"! Мы с радостью готовы включить вас сразу после внесения оплаты. Сделать это можно в личном кабинете, перейдя по ссылке ниже. Если вы сталкиваетесь с какими-либо трудностями, пожалуйста, дайти нам знать в чате на странице участия.',
                        'Перейти к оплате',
                        route('participation_index',['participation_id'=>$participation['id'],'collection_id'=>$participation['collection_id']]) . '#payment_block')
                );
            }

            // ---- Сохраняем письмо! ---- //
            $new_EmailSent = new EmailSent();
            $new_EmailSent->collection_id = $collction['id'];
            $new_EmailSent->subject = 'Требуется действие!';
            $new_EmailSent->email_text = 'Напоминание об оплате';
            $new_EmailSent->sent_to_user = substr($sent_to_users, 0, -1);
            $new_EmailSent->save();
            // ---- //// Сохраняем письмо! ---- //


        }


        $url_back = "https://pervajakniga.ru/admin_panel/col";

        // Посылаем Telegram уведомление нам
        if ($sent_to_users_amount === 0) {
            $tel_text = "📧 *Авторов для напоминания не обнаружено*";
        } else {
            $tel_text = "📧 *Успешно напомнили " . $sent_to_users_amount . " авторам об оплате*";
        }
        Notification::route('telegram', '-506622812')
            ->notify(new TelegramNotification($tel_text,
                "",
                "Админка",
                $url_back));

    }
}
