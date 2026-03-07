<?php

namespace App\Notifications\PurchasePrint;

use App\Enums\OwnBookStatusEnums;
use App\Enums\TransactionTypeEnums;
use App\Filament\Resources\printOrders\Pages\EditprintOrder;
use App\Filament\Resources\OwnBook\OwnBooks\Pages\EditOwnBook;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\HtmlString;
use NotificationChannels\Telegram\TelegramMessage;

class PurchasePrintPaymentSuccessNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public $printOrder;
    public $amount;

    public function __construct($printOrder, $amount)
    {
        $this->printOrder = $printOrder;
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
        $text = "В течение 3-х дней мы начнем печать издания {$this->printOrder->model['title']}. Вы получите отдельное уведомление по Email.";
        return (new MailMessage)
            ->subject($subject)
            ->greeting('Здравствуйте, ' . $notifiable->name . '!')
            ->line(new HtmlString($text))
            ->line("Вся подробная информация о процессе указана на странице печати:")
            ->action('Ваша страница печати', route('account.purchase-print.index', $this->printOrder['id']));
    }

    public function toTelegram($notifiable)
    {
        $subject = '💸 *Новая оплата по отдельной печати!* 💸' . "\n\n";
        $url = route('login_as_secondary_admin', ['url_redirect' => \App\Filament\Resources\PrintOrder\PrintOrders\Pages\EditPrintOrder::getUrl(['record' => $this->printOrder])]);
        $text = '*Автор:* ' . $this->printOrder->user->getUserFullName() .
            "\n" . "*Издания:* " . $this->printOrder->model['title'] .
            "\n" . "*Сумма:* " . $this->amount . " руб.";
        return TelegramMessage::create()
            ->to(getTelegramChatId())
            ->button('Подробнее', str_replace('http://localhost:8000', 'https://vk.com', $url))
            ->content($subject . $text);
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
