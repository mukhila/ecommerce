<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderCancelled extends Notification implements ShouldQueue
{
    use Queueable;

    protected $order;
    protected $reason;

    /**
     * Create a new notification instance.
     */
    public function __construct(Order $order, $reason = null)
    {
        $this->order = $order;
        $this->reason = $reason;
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

        $mail = (new MailMessage)
            ->subject('Order Cancelled - ' . $this->order->order_number)
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('We regret to inform you that your order has been cancelled.')
            ->line('Order Number: **' . $this->order->order_number . '**')
            ->line('Total Amount: **₹' . number_format($this->order->total, 2) . '**');

        if ($this->reason) {
            $mail->line('Reason: ' . $this->reason);
        }

        if ($this->order->payment_status === 'paid') {
            $mail->line('Since you have already made the payment, a refund will be processed to your original payment method within 5-7 business days.');
        }

        $mail->action('View Order Details', $dashboardUrl)
             ->line('If you have any questions or concerns, please contact our support team.')
             ->line('We apologize for any inconvenience caused.');

        return $mail;
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
            'reason' => $this->reason,
            'message' => 'Your order ' . $this->order->order_number . ' has been cancelled.',
        ];
    }
}
