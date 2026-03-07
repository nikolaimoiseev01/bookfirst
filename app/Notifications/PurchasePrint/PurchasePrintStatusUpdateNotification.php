<?php

namespace App\Notifications\PurchasePrint;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PurchasePrintStatusUpdateNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public $printOrder;
    public $oldStatus;
    public $newStatus;
    public function __construct($printOrder, $oldStatus, $newStatus)
    {
        $this->printOrder = $printOrder;
        $this->newStatus = $newStatus;
        $this->oldStatus = $oldStatus;
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
        return (new MailMessage)
            ->subject('Заявка на печать')
            ->greeting('Здравствуйте, ' . $notifiable->name . '!')
            ->line("Ваш статус заявки печати издания: '{$this->printOrder->model['title']}' изменен с '{$this->oldStatus}' на '{$this->newStatus}'!")
            ->line("Вся подробная информация о процессе указана на странице печати:")
            ->action('Ваша страница печати', route('account.purchase-print.index', $this->printOrder['id']));
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
