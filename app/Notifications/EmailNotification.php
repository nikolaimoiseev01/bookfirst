<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EmailNotification extends Notification
{
    use Queueable;

    public $email_text;
    public $email_button_text;
    public $email_button_link;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($subject, $user_name, $email_text, $email_button_text, $email_button_link)
    {
        $this->subject = $subject;
        $this->user_name = $user_name;
        $this->email_text = $email_text;
        $this->email_button_text = $email_button_text;
        $this->email_button_link = $email_button_link;

    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->greeting('Здравствуйте, ' . $this->user_name . '!')
                    ->subject($this->subject)
                    ->line($this->email_text)
                    ->action($this->email_button_text, url($this->email_button_link));
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
