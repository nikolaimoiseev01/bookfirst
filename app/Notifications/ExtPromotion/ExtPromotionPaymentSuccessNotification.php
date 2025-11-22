<?php

namespace App\Notifications\ExtPromotion;

use App\Enums\OwnBookStatusEnums;
use App\Enums\TransactionTypeEnums;
use App\Filament\Resources\ExtPromotions\Pages\EditExtPromotion;
use App\Filament\Resources\OwnBook\OwnBooks\Pages\EditOwnBook;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\HtmlString;
use NotificationChannels\Telegram\TelegramMessage;

class ExtPromotionPaymentSuccessNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public $extPromotion;
    public $amount;

    public function __construct($extPromotion, $amount)
    {
        $this->extPromotion = $extPromotion;
        $this->amount = $amount;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'telegram'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $subject = 'Оплата прошла успешно!';
        $text = "В течение 3-х дней мы начнем ваше продвижение на сайте {$this->extPromotion['site']}. Вы получите отдельное уведомление по Email.";
        return (new MailMessage)
            ->subject($subject)
            ->greeting('Здравствуйте, ' . $notifiable->name . '!')
            ->line(new HtmlString($text))
            ->line("Вся подробная информация о процессе указана на странице продвижения:")
            ->action('Ваша страница продвижения', route('account.ext_promotion.index', $this->extPromotion['id']));
    }

    public function toTelegram($notifiable)
    {
        $subject = '💸 *Новая оплата по продвижению!* 💸' . "\n\n";

        $text = '*Автор:* ' . $this->extPromotion->user->getUserFullName() .
            "\n" . "*Сумма:* " . $this->amount . " руб.";
        $url = route('login_as_admin', ['url_redirect' => EditExtPromotion::getUrl(['record' => $this->extPromotion])]);
        $url = str_replace('http://localhost:8000', 'https://vk.com', $url);
        return TelegramMessage::create()
            ->to(getTelegramChatId())
            ->content($subject . $text)
            ->button('Подробнее', $url);
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
