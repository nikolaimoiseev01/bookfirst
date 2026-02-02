<?php

namespace App\Notifications\Collection;

use App\Models\Chat\Message;
use App\Models\Collection\Participation;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Auth;

class CollectionWinnerNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public $collection;
    public $place;
    public $participationId;
    public function __construct($collection, $place, $participationId)
    {
        $this->collection = $collection;
        $this->place = $place;
        $this->participationId = $participationId;
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
        $participation = Participation::find($this->participationId);
        $text= "Поздравляем! Вы заняли " . $this->place . " место в конкурсе авторов сборника '" . $this->collection['title'] . "'! " .
            "Сейчас необходимо прислать небольшой блок информации о себе для добавления в сборник. Пожалуйста, отправьте его в чате на странице участия.";

        Message::create([
            'chat_id' => $participation->chat['id'],
            'user_id' => 2,
            'text' => $text
        ]);

        return (new MailMessage)
            ->subject('Вы были выбраны призёром конкурса!')
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
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
