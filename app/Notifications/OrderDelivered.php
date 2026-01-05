<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderDelivered extends Notification implements ShouldQueue
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
        $dashboardUrl = route('dashboard');

        return (new MailMessage)
            ->subject('Your Order Has Been Delivered - ' . $this->order->order_number)
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('Great news! Your order has been successfully delivered.')
            ->line('Order Number: **' . $this->order->order_number . '**')
            ->line('Total Amount: **₹' . number_format($this->order->total, 2) . '**')
            ->line('We hope you enjoy your purchase!')
            ->action('View Order Details', $dashboardUrl)
            ->line('If you have any questions or concerns about your order, please contact our support team.')
            ->line('We would love to hear your feedback. Please consider leaving a review for the products you purchased.')
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
            'total' => $this->order->total,
            'message' => 'Your order ' . $this->order->order_number . ' has been delivered successfully.',
        ];
    }
}
