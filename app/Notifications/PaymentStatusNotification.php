<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PaymentStatusNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected Order $order;
    protected string $newStatus;

    public function __construct(Order $order, string $newStatus)
    {
        $this->order     = $order;
        $this->newStatus = $newStatus;
    }

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $customerName = $notifiable->name ?? $this->order->guest_name ?? 'Customer';
        $dashboardUrl = route('dashboard');

        $mail = (new MailMessage)->greeting('Hello ' . $customerName . '!');

        switch ($this->newStatus) {

            case 'paid':
                $mail->subject('Payment Confirmed - ' . $this->order->order_number)
                     ->line('Great news! Your payment has been successfully confirmed.')
                     ->line('**Order Number:** ' . $this->order->order_number)
                     ->line('**Amount Paid:** ₹' . number_format($this->order->total, 2))
                     ->line('**Payment Method:** ' . strtoupper($this->order->payment_method))
                     ->action('View Order', $dashboardUrl)
                     ->line('Your order is now being processed and will be shipped soon.')
                     ->line('Thank you for shopping with us!');
                break;

            case 'failed':
                $mail->subject('Payment Failed - ' . $this->order->order_number)
                     ->line('Unfortunately, your payment could not be processed.')
                     ->line('**Order Number:** ' . $this->order->order_number)
                     ->line('**Amount:** ₹' . number_format($this->order->total, 2))
                     ->line('Please try placing a new order or contact our support team if the issue persists.')
                     ->action('View Order', $dashboardUrl)
                     ->line('We apologize for the inconvenience.');
                break;

            case 'refunded':
                $mail->subject('Refund Processed - ' . $this->order->order_number)
                     ->line('Your refund has been successfully processed.')
                     ->line('**Order Number:** ' . $this->order->order_number)
                     ->line('**Refund Amount:** ₹' . number_format($this->order->total, 2))
                     ->line('The refund will be credited to your original payment method within 5–7 business days.')
                     ->action('View Order Details', $dashboardUrl)
                     ->line('If you have any questions, please contact our support team.')
                     ->line('Thank you for your patience!');
                break;

            default:
                $mail->subject('Payment Update - ' . $this->order->order_number)
                     ->line('Your payment status for order **' . $this->order->order_number . '** has been updated to: **' . ucfirst($this->newStatus) . '**.')
                     ->action('View Order', $dashboardUrl);
                break;
        }

        return $mail;
    }

    public function toArray(object $notifiable): array
    {
        $messages = [
            'paid'     => 'Payment confirmed for order ' . $this->order->order_number . '.',
            'failed'   => 'Payment failed for order ' . $this->order->order_number . '.',
            'refunded' => 'Refund processed for order ' . $this->order->order_number . '.',
        ];

        $titles = [
            'paid'     => 'Payment Confirmed',
            'failed'   => 'Payment Failed',
            'refunded' => 'Refund Processed',
        ];

        return [
            'title'          => $titles[$this->newStatus]   ?? 'Payment Update',
            'order_id'       => $this->order->id,
            'order_number'   => $this->order->order_number,
            'payment_status' => $this->newStatus,
            'total'          => $this->order->total,
            'message'        => $messages[$this->newStatus] ?? 'Payment status updated to ' . $this->newStatus . ' for order ' . $this->order->order_number . '.',
        ];
    }
}
