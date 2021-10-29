<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\Telegram\TelegramChannel;
use NotificationChannels\Telegram\TelegramMessage;

class new_participation extends Notification
{

    protected $collection_name;
    protected $author_name;
    protected $total_price;
    protected $pages;
    protected $prints_needed;

    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($collection_name, $author_name, $total_price, $pages, $prints_needed)
    {
        $this->collection_name = $collection_name;
        $this->author_name = $author_name;
        $this->total_price = $total_price;
        $this->pages = $pages;
        $this->prints_needed = $prints_needed;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return [TelegramChannel::class];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
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
     * @param mixed $notifiable
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
            ->content("💥 Новая заявка в " . $this->collection_name . "! 💥" .
                "\n\n**Автор:** " . $this->author_name .
                "\n**Страниц:** " . $this->pages . " стр." .
                "\n**Печать:** " . $this->prints_needed . " шт." .
                "\n**Итого:** " . $this->total_price . " руб."
            )

            // (Optional) Blade template for the content.
            // ->view('notification', ['url' => $url])

            // (Optional) Inline Buttons
            ->button('К заявкам', route('homePortal') . "/admin_panel/collections/new_participants");
    }
}
