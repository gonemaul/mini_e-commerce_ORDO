<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AssignPermissionNotif extends Notification implements ShouldQueue
{
    use Queueable;
    private $assign_permission;
    private $user_assigned;

    /**
     * Create a new notification instance.
     */
    public function __construct($assign_permission, $user_assigned)
    {
        $this->assign_permission = $assign_permission;
        $this->user_assigned = $user_assigned;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
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
            'title' => 'Users Assigned New Permissions',
            'message' => 'Users granted new permissions with email : '. $this->user_assigned->email. ' ,that gives permission to the user with an email : ' . $this->assign_permission->email,
            'url' => url('users/'. $this->user_assigned->id) ,
            'type' => 'mdi mdi-account-key text-danger'
        ];
    }
}