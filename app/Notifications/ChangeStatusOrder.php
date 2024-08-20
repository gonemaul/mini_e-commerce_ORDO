<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ChangeStatusOrder extends Notification implements ShouldQueue
{
    use Queueable;
    private $order;

    /**
     * Create a new notification instance.
     */
    public function __construct($order)
    {
        $this->order = $order;
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
    public function toMail(object $notifiable): MailMessage
    {
        if($notifiable->is_admin == true){
            $url = 'orders/' . $this->order->id;
        }else{
            $url = 'api/orders/history';
        }
        return (new MailMessage)
                    ->greeting('Hello, ' . $notifiable->name)
                    ->line('Change status order with ID '. $this->order->order_id)
                    ->action('Detail order', url($url))
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        if($notifiable->is_admin == true){
            $url = 'orders/' . $this->order->id;
        }else{
            $url = 'api/orders/history';
        }
        return [
            'title' => 'Change Status Order',
            'message' => 'Change status order with ID '. $this->order->order_id ,
            'url' => url($url) ,
            'type' => 'mdi-cart text-success'
        ];
    }
}