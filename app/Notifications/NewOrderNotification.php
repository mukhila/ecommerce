<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Order;

class NewOrderNotification extends Notification
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
                    ->subject('New Order Received - ' . $this->order->order_number)
                    ->greeting('Hello Admin!')
                    ->line('A new order has been placed on your store.')
                    ->line('**Order Number:** ' . $this->order->order_number)
                    ->line('**Customer:** ' . ($this->order->user ? $this->order->user->name : $this->order->guest_name))
                    ->line('**Order Total:** ₹' . number_format($this->order->total, 2))
                    ->line('**Payment Method:** ' . strtoupper($this->order->payment_method))
                    ->line('**Items:** ' . $this->order->items->count())
                    ->action('View Order Details', url('/admin/orders/' . $this->order->id))
                    ->line('Please process this order as soon as possible.');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'order_id' => $this->order->id,
            'order_number' => $this->order->order_number,
            'customer_name' => $this->order->user ? $this->order->user->name : $this->order->guest_name,
            'total' => $this->order->total,
            'message' => 'New order ' . $this->order->order_number . ' has been placed.'
        ];
    }
}
