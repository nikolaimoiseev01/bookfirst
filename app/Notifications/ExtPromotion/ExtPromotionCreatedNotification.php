<?php

namespace App\Notifications\ExtPromotion;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\Telegram\TelegramMessage;

class ExtPromotionCreatedNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    private $newExtPromotion;

    public function __construct($newExtPromotion)
    {
        $this->newExtPromotion = $newExtPromotion;
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
        $subject = '💥 *Новая заявка на продвижение!* 💥';
        $userName = $this->newExtPromotion->user->getUserFullName();
        $promocode_info = $this->newExtPromotion['promocode_id'] ?? null ? "*Промокод*: " . $this->newExtPromotion->promocode['name'] . "\n" : "";
        $text = "*Автор*: {$userName}\n" .
            "*Логин*: {$this->newExtPromotion['login']}\n" .
            "*Сайт*: {$this->newExtPromotion['site']}\n" .
            $promocode_info .
            "*Дней*: {$this->newExtPromotion['days']}\n" .
            "*Общая стоимость*: {$this->newExtPromotion['price_total']}";
        return TelegramMessage::create()
            ->to(getTelegramChatId('extPromotion'))
            ->content("$subject\n\n$text");
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
