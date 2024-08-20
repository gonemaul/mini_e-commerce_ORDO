<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewOrder extends Notification implements ShouldQueue
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
            return (new MailMessage)
                    ->greeting('Hello, ' . $notifiable->name)
                    ->line('New Order with ID '. $this->order->order_id)
                    ->action('Detail order', url('orders/' . $this->order->id))
                    ->line('Thank you for using our application!');
        }else{
            return (new MailMessage)
                    ->greeting('Hello, ' . $notifiable->name)
                    ->line('New Order with ID '. $this->order->order_id)
                    ->line('Please make the payment immediately!!!')
                    ->action('Download invoice', url('invoice/' . $this->order->order_id))
                    ->line('Thank you for using our application!');
        }

    }
    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        if($notifiable->is_admin == true){
            return [
                'title' => 'New Order',
                'message' => 'New Order with ID '. $this->order->order_id ,
                'url' => url('orders/' . $this->order->id) ,
                'type' => 'mdi-cart text-warning'
            ];
        }else{
            $url = 'api/invoice/' . $this->order->order_id;
            return [
                'title' => 'New Order',
                'message' => 'New Order with ID '. $this->order->order_id . ', Please make the payment immediately!!!',
                'url' => url('api/invoice/' . $this->order->order_id) ,
            ];
        }

    }
}