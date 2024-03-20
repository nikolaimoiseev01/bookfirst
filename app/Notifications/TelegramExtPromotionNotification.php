<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\Telegram\TelegramChannel;
use NotificationChannels\Telegram\TelegramMessage;

class TelegramExtPromotionNotification extends Notification
{
    use Queueable;

    private $title;
    private $text;
    private $button_text;
    private $button_link;

    /**
     * Create a new notification instance.
     */
    public function __construct($title, $text, $button_text, $button_link)
    {
        $this->title = $title;
        $this->text = $text;
        $this->button_text = $button_text;
        $this->button_link = $button_link ?? null;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return [TelegramChannel::class];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toTelegram(object $notifiable): MailMessage
    {
        if($this->button_link ?? null) {
            return TelegramMessage::create()
                ->content(((ENV('APP_DEBUG')) ? "ТЕСТ \n\n" : '') . $this->title . "\n\n" . $this->text)
                ->token('6954081611:AAHvQVgoerJhLM3nJU30X0i4pYAVIWFwu6g')
                ->button($this->button_text, $this->button_link);
        } else {
            return TelegramMessage::create()
                ->token('6954081611:AAHvQVgoerJhLM3nJU30X0i4pYAVIWFwu6g')
                ->content(((ENV('APP_DEBUG')) ? "ТЕСТ \n\n" : '') . $this->title . "\n\n" . $this->text);
        }
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
