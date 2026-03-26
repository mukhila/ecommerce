<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\SupportTicket;
use App\Models\SupportTicketReply;

class TicketCustomerReplied extends Notification
{
    use Queueable;

    public function __construct(
        protected SupportTicket $ticket,
        protected SupportTicketReply $reply
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Customer Reply on Ticket – ' . $this->ticket->ticket_number)
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('A customer has replied to a support ticket assigned to you.')
            ->line('**Ticket:** ' . $this->ticket->ticket_number . ' – ' . $this->ticket->subject)
            ->line('**Customer:** ' . $this->ticket->name . ' (' . $this->ticket->email . ')')
            ->line('**Customer\'s Message:**')
            ->line($this->reply->message)
            ->action('View Ticket', url('/admin/support/' . $this->ticket->ticket_number));
    }
}
