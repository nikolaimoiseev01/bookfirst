<?php

namespace App\Notifications\OwnBook;

use App\Enums\OwnBookStatusEnums;
use App\Enums\TransactionTypeEnums;
use App\Filament\Resources\OwnBook\OwnBooks\Pages\EditOwnBook;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\HtmlString;
use NotificationChannels\Telegram\TelegramMessage;

class OwnBookPaymentSuccessNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public $ownBook;
    public $amount;
    public $transactionType;

    public function __construct($ownBook, $amount, $transactionType)
    {
        $this->ownBook = $ownBook;
        $this->amount = $amount;
        $this->transactionType = $transactionType;
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
        $status = OwnBookStatusEnums::WORK_IN_PROGRESS->value;
        $text = match ($this->transactionType) {
            TransactionTypeEnums::OWN_BOOK_WO_PRINT => "Мы успешно приняли оплату (без печати) в рамках заявки на издание книги <b>'{$this->ownBook['title']}'</b>! Сейчас статус заявки сменился на '$status'. В течение 10 дней мы пришлем на согласование макеты внутреннего блока и обложки. Вы получите отдельные уведомления на Email в том числе.",
            TransactionTypeEnums::OWN_BOOK_PRINT => "Мы успешно приняли оплату заказа печатных экземпляров в рамках заявки на издание книги <b>'{$this->ownBook['title']}'</b>! Сейчас статус заявки сменился на '$status'. В течение 3 дней мы подготовим все утвержденные макеты к профессиональная печати. Как только мы запустим заказ в работу, статус издания изменится. Вы получите отдельное уведомление на Email в том числе."
        };
        return (new MailMessage)
            ->subject($subject)
            ->greeting('Здравствуйте, ' . $notifiable->name . '!')
            ->line(new HtmlString($text))
            ->line("Вся подробная информация о процессе указана на странице издания:")
            ->action('Ваша страница издания', route('account.own_book.index', $this->ownBook['id']));
    }

    public function toTelegram($notifiable)
    {
        $subject = match ($this->transactionType) {
            TransactionTypeEnums::OWN_BOOK_WO_PRINT =>  '💸 *Новая оплата по книге!* 💸' . "\n\n",
            TransactionTypeEnums::OWN_BOOK_PRINT =>  '💸 *Новая оплата печати по книге!* 💸' . "\n\n"
        };

        $text = '*Автор:* ' . $this->ownBook['author'] .
            "\n" . "*Книга:* " . $this->ownBook['title'] .
            "\n" . "*Сумма:* " . $this->amount . " руб.";
        $url = route('login_as_secondary_admin', ['url_redirect' => EditOwnBook::getUrl(['record' => $this->ownBook])]);
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
