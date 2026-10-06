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
        $prizes = [
            1 => 'Вам полагаются: бесплатное участие, печатный экземпляр сборника и пересылка за наш счёт. Ответьте, пожалуйста, в чате на странице издания что предпочитаете: возврат средств или промокод на бесплатное участие? ',
            2 => 'Вам полагаются: скидка 50 % на участие в текущем сборнике и промокод на 50 % для участия в следующем сборнике. Ваш промокод: WINNER_50. Напишите, пожалуйста, в чате на странице издания номер телефона и банк для возврата половины стоимости участия',
            3 => 'Ваш приз: бесплатный печатный экземпляр сборника и его пересылка за наш счёт при наличии заказа печатных экземпляров.'
        ];
        $text= "Поздравляем! Вы заняли " . $this->place . " место в конкурсе авторов сборника '" . $this->collection['title'] . "'! " .
            "Сейчас необходимо прислать небольшой блок информации о себе для добавления в сборник. Пожалуйста, отправьте его в чате на странице участия. " . $prizes[$this->place];

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
