<?php

namespace App\Notifications;

use App\Models\Category;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class NewProduct extends Notification implements ShouldQueue
{
    use Queueable;
    private $product;
    private $category;

    /**
     * Create a new notification instance.
     */
    public function __construct($product,$categoryID)
    {
        $this->product = $product;
        $this->category =  Category::findOrFail($categoryID);
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
        return (new MailMessage)
                    ->greeting('Hello, ' . $notifiable->name)
                    ->line('New Product available with category '. $this->category->name)
                    ->action('Detail product', url('api/products/'. $this->product->id))
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
            'title' => 'New Product',
            'message' => 'New Product available with category '. $this->category->name,
            'url' => url('api/products/'. $this->product->id)
        ];
    }
}