<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewUser extends Notification implements ShouldQueue
{
    use Queueable;
    private $user;

    /**
     * Create a new notification instance.
     */
    public function __construct($user)
    {
        $this->user = $user;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database','mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable,): MailMessage
    {
        return (new MailMessage)
                    ->greeting('Hello, ' . $notifiable->name)
                    ->line('New user registration with email ' . $this->user->email)
                    ->action('Detail user', url('users/'. $this->user->id))
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'New User Registration',
            'message' => 'New user registered with email '. $this->user->email,
            'url' => url('users/'. $this->user->id) ,
            'type' => 'mdi-account-multiple text-primary'
        ];
    }
}
