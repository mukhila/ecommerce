<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Order;

class OrderPlaced extends Notification implements ShouldQueue
{
    use Queueable;

    protected $order;

    /**
     * Create a new notification instance.
     */
    public function __construct(Order $order)
    {
        $this->order = $order->loadMissing('items');
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
        $mail = (new MailMessage)
            ->subject('Order Confirmation - ' . $this->order->order_number)
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('Thank you for your order! Your order has been placed successfully.')
            ->line('**Order Number:** ' . $this->order->order_number)
            ->line('**Payment Method:** ' . strtoupper($this->order->payment_method));

        // List ordered items
        foreach ($this->order->items as $item) {
            $line = '• ' . $item->product_name;
            if ($item->size_label) {
                $line .= ' (Size: ' . $item->size_label . ')';
            }
            $line .= ' × ' . $item->quantity . ' — ₹' . number_format($item->total, 2);
            $mail->line($line);
        }

        $mail->line('**Order Total:** ₹' . number_format($this->order->total, 2))
             ->action('View Order', route('order.success', $this->order->id))
             ->line('We will notify you once your order is shipped.')
             ->line('Thank you for shopping with us!');

        return $mail;
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
