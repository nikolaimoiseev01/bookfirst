<?php

namespace App\Notifications;

use App\Models\Collection\Collection;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\Telegram\TelegramMessage;

class OwnBookCreatedNotification extends Notification
{
    use Queueable;

    private $text;
    private $subject;
    private $url;
    private Collection $collection;
    public function __construct($subject, $text, $url)
    {
        $this->text = $text;
        $this->subject = $subject;
        $this->url = $url;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['telegram'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toTelegram(object $notifiable): TelegramMessage
    {
        return TelegramMessage::create()
            ->to(getTelegramChatId())
            ->content($this->subject . $this->text)
            ->button('Подробнее', $this->url);
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
