<?php

namespace App\Notifications\ExtPromotion;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ExtPromotionStatusUpdateNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public $extPromotion;
    public $oldStatus;
    public $newStatus;
    public function __construct($extPromotion, $oldStatus, $newStatus)
    {
        $this->extPromotion = $extPromotion;
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
            ->subject('Заявка на продвижение')
            ->greeting('Здравствуйте, ' . $notifiable->name . '!')
            ->line("Ваш статус заявки продвижения на сайте: '{$this->extPromotion['site']}' изменен с '{$this->oldStatus}' на '{$this->newStatus}'!")
            ->line("Вся подробная информация о процессе указана на странице продвижения:")
            ->action('Ваша страница участия', route('account.ext_promotion.index', $this->extPromotion['id']));
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
