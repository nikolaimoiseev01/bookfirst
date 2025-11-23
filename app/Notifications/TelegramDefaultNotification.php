<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\Telegram\TelegramMessage;

class TelegramDefaultNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public $subject;
    public $text;
    public $url;
    public $chat;

    public function __construct($subject, $text, $url=null, $chat=null)
    {
        $this->subject = $subject;
        $this->text = $text;
        $this->url = $url;
        $this->chat = $chat;
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
    public function toTelegram($notifiable)
    {
        $message = TelegramMessage::create()
            ->to(getTelegramChatId($this->chat))
            ->content("*$this->subject*\n\n$this->text");
        if($this->url) {
            $this->url = str_replace('http://localhost:8000', 'https://vk.com', $this->url);
            $message->button('Подробнее', $this->url);
        }
        return $message;
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
