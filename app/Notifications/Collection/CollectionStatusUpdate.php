<?php

namespace App\Notifications\Collection;

use App\Enums\CollectionStatusEnums;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class CollectionStatusUpdate extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    private $collection;
    private $newStatus;
    private $participationId;

    public function __construct($collection, $participationId, $newStatus)
    {
        $this->collection = $collection;
        $this->participationId = $participationId;
        $this->newStatus = $newStatus->value;
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
        $subject = 'Статус сборника сменился!';
        $text =  "Спешим вам сообщить, что произошла смена этапа издания сборника: '" . $this->collection['title'] .
            "'! Сборник сменил свой статус на \"{$this->newStatus}\"";

        if ($this->newStatus == CollectionStatusEnums::PREVIEW->value) {
            $subject = 'Началась предварительная проверка сборника!';
            $text = "Спешим вам сообщить, что произошла смена этапа издания сборника: '" . $this->collection['title'] .
                "'! Сборник сменил свой статус на \"предварительная проверка\". Теперь его можно скачать на странице участия и внести правки. " .
                "Срок внесения изменений: до " . formatDate($this->collection['date_preview_end'], 'j F') . " (19:59 МСК). ";
        }
        if ($this->newStatus == CollectionStatusEnums::PRINTING->value) {
            $subject = 'Началась печать сборника!';
            $text = "Спешим вам сообщить, что произошла смена этапа издания сборника: '" . $this->collection['title'] .
                "'! В сборнике были учтены все исправления, и сейчас начинается печать экземпляров. " .
                "Обычно это занимает 14 рабочих дней. Как только экземпляры будут напечатаны, Вы получите оповещние об этом по Email. "
                . "Далее в личном кабинете на странице участия Вы сможете отследить свою посылку. " .
                "Вся подробная информация об издании сборника и вашем процессе указана на странице участия.";
        }
        if ($this->newStatus == CollectionStatusEnums::DONE->value) {
            $subject = 'Издания сборника завершено!';
            $text = "Произошла смена этапа издания сборника: '" . $this->collection['title'] .
                "'! Спешим сообщить, что все печатные экземпляры были успешно отправлены в указанные пункты назначения. На странице участия Вы всегда можете отследить нахождение лично Вашей посылки.";
        }
        return (new MailMessage)
            ->subject($subject)
            ->greeting('Здравствуйте, ' . $notifiable->name . '!')
            ->line($text)
            ->line("Вся подробная информация об издании сборника и вашем процессе указана на странице участия.")
            ->action('Ваша страница участия', route('account.participation.index', $this->participationId));
    }


    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public
    function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
