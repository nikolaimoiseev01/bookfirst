<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\Telegram\TelegramChannel;
use NotificationChannels\Telegram\TelegramMessage;

class TelegramNotification extends Notification
{
    use Queueable;

    private $title;
    private $text;
    private $button_text;
    private $button_link;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($title, $text, $button_text, $button_link)
    {
        $this->title = $title;
        $this->text = $text;
        $this->button_text = $button_text;
        $this->button_link = $button_link;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return [TelegramChannel::class];
    }


    public function toTelegram($notifiable)
    {

        return TelegramMessage::create()
            // Markdown supported.

            ->content(((ENV('APP_DEBUG')) ? "ТЕСТ \n\n" : '') . $this->title . "\n\n" . $this->text)
            ->button($this->button_text, $this->button_link);
    }
}
