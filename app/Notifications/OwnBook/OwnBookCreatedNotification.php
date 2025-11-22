<?php

namespace App\Notifications\OwnBook;

use App\Models\Collection\Collection;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\Telegram\TelegramMessage;

class OwnBookCreatedNotification extends Notification
{
    use Queueable;

    private $text;
    private $subject;
    private $url;
    private $channel;
    public function __construct($subject, $text, $url, $channel = null)
    {
        $this->text = $text;
        $this->subject = $subject;
        $this->url = $url;
        $this->channel = $channel;
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
        $this->url = str_replace('http://localhost:8000', 'https://vk.com', $this->url);
        return TelegramMessage::create()
            ->to(getTelegramChatId($this->channel))
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
