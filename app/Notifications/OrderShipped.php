<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderShipped extends Notification implements ShouldQueue
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
     *
     * @return array<int, string>
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
        $trackingUrl = route('order.tracking', $this->order->id);

        return (new MailMessage)
            ->subject('Your Order Has Been Shipped - ' . $this->order->order_number)
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('Good news! Your order has been shipped and is on its way to you.')
            ->line('Order Number: **' . $this->order->order_number . '**')
            ->line('Tracking Number: **' . ($this->order->tracking_number ?? 'Will be updated soon') . '**')
            ->line('Courier Service: **' . ($this->order->courier_name ?? 'N/A') . '**')
            ->line('Estimated Delivery: **' . ($this->order->estimated_delivery_date ? \Carbon\Carbon::parse($this->order->estimated_delivery_date)->format('d M Y') : 'Will be updated soon') . '**')
            ->action('Track Your Order', $trackingUrl)
            ->line('Thank you for shopping with us!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'order_id' => $this->order->id,
            'order_number' => $this->order->order_number,
            'tracking_number' => $this->order->tracking_number,
            'courier_name' => $this->order->courier_name,
            'estimated_delivery_date' => $this->order->estimated_delivery_date,
            'message' => 'Your order ' . $this->order->order_number . ' has been shipped.',
        ];
    }
}
