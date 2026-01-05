<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Order;

class OrderPlaced extends Notification
{
    use Queueable;

    protected $order;

    /**
     * Create a new notification instance.
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->subject('Order Confirmation - ' . $this->order->order_number)
                    ->greeting('Hello ' . $notifiable->name . '!')
                    ->line('Thank you for your order!')
                    ->line('Your order has been placed successfully.')
                    ->line('**Order Number:** ' . $this->order->order_number)
                    ->line('**Order Total:** ₹' . number_format($this->order->total, 2))
                    ->line('**Payment Method:** ' . strtoupper($this->order->payment_method))
                    ->action('View Order', url('/dashboard'))
                    ->line('We will notify you once your order is shipped.')
                    ->line('Thank you for shopping with us!');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'order_id' => $this->order->id,
            'order_number' => $this->order->order_number,
            'total' => $this->order->total,
            'message' => 'Your order ' . $this->order->order_number . ' has been placed successfully.'
        ];
    }
}
