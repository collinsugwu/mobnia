<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class Welcome extends Notification
{
    use Queueable;


    public function __construct()
    {
    }

    /**
     * Get the notification"s delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ["database", "mail"];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject(__('Welcome'))
            ->greeting(__('Welcome, :name', ['name' => $notifiable->first_name]))
            ->line(__('We are thrilled you decided to join us! Hats off on making this excellent decision.'))
            ->line(__('Everything is waiting for you on your dashboard'))
            ->action(__("Let's Get Started"), url('/dashboard'))
            ->line(__('Thank you for choosing us'));
    }


    /**
     * Get the database representation of the notification.
     *
     * @param mixed $notifiable
     * @return MailMessage|array
     */
    public function toDatabase($notifiable)
    {
        return [
            'msg' => "Hats off on making this excellent decision! Let's get started",
            'link' => url('/dashboard')
        ];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }

}
