<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\Telegram\TelegramChannel;
use NotificationChannels\Telegram\TelegramMessage;

class new_own_book extends Notification
{

    protected $collection_name;
    protected $total_price;
    protected $pages;
    protected $prints_needed;

    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($user_name, $author_name, $title, $inside_price, $cover_price, $print_price, $promo_price)
    {
        $this->user_name = $user_name;
        $this->author_name = $author_name;
        $this->title = $title;
        $this->inside_price = number_format($inside_price, 2, ',', ' ');
        $this->cover_price = $cover_price;
        if (intval($cover_price) > 300) {
            $this->cover_need = 'нужно делать';
        }
        else {
            $this->cover_need = 'готовая от автора';
        }

        $this->print_price = number_format($print_price, 2, ',', ' ');;
        $this->promo_price = number_format($promo_price, 2, ',', ' '); ;
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


    public function toTelegram($notifiable)
    {
        return TelegramMessage::create()
            // Markdown supported.
            ->content("💥 Новая книга от " . $this->user_name . "! 💥" .
                "\n\n**Название:** " . $this->author_name . ": " . $this->title .
                "\n**Редактура:** " . $this->inside_price . " руб." .
                "\n**Обложка:** " . $this->cover_need .
                "\n**Печать:** " . $this->print_price . " руб." .
                "\n**Промо:** " . $this->promo_price . " руб."
            )

            // (Optional) Blade template for the content.
            // ->view('notification', ['url' => $url])

            // (Optional) Inline Buttons
            ->button('Все книги', "http://127.0.0.1:8000/admin_panel/own_books");
    }
}
