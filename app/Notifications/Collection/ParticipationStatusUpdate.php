<?php

namespace App\Notifications\Collection;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ParticipationStatusUpdate extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */

    public $participation;
    public $oldStatus;
    public $newStatus;
    public function __construct($participation, $oldStatus, $newStatus)
    {
        $this->participation = $participation;
        $this->newStatus = $newStatus;
        $this->oldStatus = $oldStatus;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {

        return (new MailMessage)
            ->subject('Статус участия в сборнике')
            ->greeting('Здравствуйте, ' . $notifiable->name . '!')
            ->line("Статус участия в сборнике: '" . $this->participation->collection['title'] . "' изменен с '" . $this->oldStatus . "' на '" . $this->newStatus . "'!")
            ->line("Вся подробная информация об издании сборника и вашем процессе указана на странице участия:")
            ->action('Ваша страница участия', route('account.participation.index', $this->participation['id']));
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
