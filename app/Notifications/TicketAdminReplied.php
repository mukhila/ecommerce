<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\SupportTicket;
use App\Models\SupportTicketReply;

class TicketAdminReplied extends Notification
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
            ->subject('Update on Your Support Ticket – ' . $this->ticket->ticket_number)
            ->greeting('Hello ' . $this->ticket->name . '!')
            ->line('Our support team has replied to your ticket.')
            ->line('**Ticket:** ' . $this->ticket->ticket_number . ' – ' . $this->ticket->subject)
            ->line('**Status:** ' . ucfirst(str_replace('_', ' ', $this->ticket->status)))
            ->line('**Support Agent\'s Message:**')
            ->line($this->reply->message)
            ->line('You can reply by logging in to your account and visiting your ticket.');
    }
}
