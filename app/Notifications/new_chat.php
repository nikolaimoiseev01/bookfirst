<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\Telegram\TelegramChannel;
use NotificationChannels\Telegram\TelegramMessage;

class new_chat extends Notification
{
    use Queueable;

    public $name;
    public $surname;
    public $title;
    public $message;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($name, $surname, $title, $message)
    {
        $this->name = $name;
        $this->surname = $surname;
        $this->title = $title;
        $this->message = $message;
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

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }

    public function toTelegram($notifiable)
    {
        return TelegramMessage::create()
            // Markdown supported.
            ->content("📌Открыт новый чат!📌".
                "\n\n**Автор:** ". $this->name . " ". $this->surname .
                "\n**Тема:** ". $this->title .
                "\n**Сообщение:** " . $this->message
            )

            // (Optional) Blade template for the content.
            // ->view('notification', ['url' => $url])

            // (Optional) Inline Buttons
            ->button('К заявкам', "http://127.0.0.1:8000/admin_panel/new_participants");
    }
}
