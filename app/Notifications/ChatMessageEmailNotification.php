<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ChatMessageEmailNotification extends Notification
{
    use Queueable;

    public $chat;

    public function __construct($chat)
    {
        $this->chat = $chat;
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
        $subject = 'Новое сообщение в чате!';
        $text = "Вы получили новое сообщение в чате '{$this->chat['title']}'.";
        $url = match ($this->chat['model_type']) {
            'Collection', 'OwnBook', 'ExtPromotion' => $this->chat->model->accountIndexPage(),
            default => route('account.chats', ['curChatId', $this->chat['id']])
        };
        return (new MailMessage)
            ->subject($subject)
            ->greeting('Здравствуйте, ' . $notifiable->name . '!')
            ->line($text)
            ->action('Подробнее', $url);
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
