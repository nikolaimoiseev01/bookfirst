<?php

namespace App\Notifications\OwnBook;

use App\Enums\OwnBookStatusEnums;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\HtmlString;

class OwnBookStatusUpdateNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public $ownBook;
    public $statusType;
    public $oldStatus;
    public $newStatus;

    public function __construct($ownBook, $statusType, $oldStatus, $newStatus)
    {
        $this->ownBook = $ownBook;
        $this->statusType = $statusType;
        $this->oldStatus = $oldStatus;
        $this->newStatus = $newStatus;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $typeString = match ($this->statusType) {
            'status_general' => 'Общий статус',
            'status_inside' => 'Статус работы со внутренним блоком',
            'status_cover' => 'Статус работы с обложкой',
        };
        $subject = 'Статус издания книги';
        $text =  "{$typeString} по книге: '" . $this->ownBook['title'] . "' изменен с '" . $this->oldStatus . "' на '" . $this->newStatus . "'!";

        if ($this->statusType == 'status_general' && $this->newStatus == OwnBookStatusEnums::PAYMENT_REQUIRED->value) {
            $subject = 'Мы готовы начать издание вашей книги!';
            $text = "Спешим сообщить, что мы успешно проверили вашу заявку на издание книги <b>'{$this->ownBook['title']}'</b>! Сейчас статус заявки сменился на '$this->newStatus'. Сразу после оплаты мы начнем работы по подготовке макетов.";
        }
        return (new MailMessage)
            ->subject($subject)
            ->greeting('Здравствуйте, ' . $notifiable->name . '!')
            ->line(new HtmlString($text))
            ->line("Вся подробная информация о процессе указана на странице издания:")
            ->action('Ваша страница издания', route('account.own_book.index', $this->ownBook['id']));
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
