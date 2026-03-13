<?php

namespace App\Notifications\Collection;

use App\Models\Collection\Collection;
use App\Models\Collection\Participation;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\Telegram\TelegramMessage;

class PaymentParticipationSuccessNotification extends Notification
{
    use Queueable;

    private Collection $collection;
    private Participation $participation;
    private int $amount;
    /**
     * Create a new notification instance.
     */
    public function __construct(Participation $participation, Collection $collection, int $amount)
    {
        $this->collection = $collection;
        $this->participation = $participation;
        $this->amount = $amount;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'telegram'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $line_1 = "Отлично, вы успешно оплатили заявку в сборике: '" . $this->collection['title'] .
            "'. Следующий этап (предварительная проверка сборника) будет доступен " . formatDate($this->collection['date_preview_start'], 'j F') . "! ";

        return (new MailMessage)
            ->subject('Успешная оплата участия в сборнике')
            ->greeting('Здравствуйте, ' . $notifiable->name . '!')
            ->line($line_1)
            ->line("Вся подробная информация об издании сборника и вашем процессе указана на странице участия:")
            ->action('Ваша страница участия', route('account.participation.index', $this->participation['id']));
    }

    public function toTelegram($notifiable)
    {
        $subject = '💸 *Новая оплата по сборинку!* 💸' . "\n\n";
        $text =  '*Автор:* ' . $this->participation['author_name'] .
            "\n" . "*Сборник:* " . $this->collection['title'] .
            "\n" . "*Сумма:* " . $this->amount . " руб.";

        return TelegramMessage::create()
            ->to(getTelegramChatId())
            ->content($subject . $text);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
