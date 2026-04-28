<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\SupportTicket;

class TicketCreated extends Notification
{
    use Queueable;

    public function __construct(protected SupportTicket $ticket) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $responseTime = match($this->ticket->priority) {
            'high'   => '2–4 hours',
            'medium' => '12–24 hours',
            default  => '24–48 hours',
        };

        return (new MailMessage)
            ->subject('Support Ticket Received – ' . $this->ticket->ticket_number)
            ->greeting('Hello ' . $this->ticket->name . '!')
            ->line('Thank you for contacting Jangokids support. We have received your request.')
            ->line('**Ticket Number:** ' . $this->ticket->ticket_number)
            ->line('**Subject:** ' . $this->ticket->subject)
            ->line('**Category:** ' . $this->ticket->category)
            ->line('**Priority:** ' . ucfirst($this->ticket->priority))
            ->line('**Expected Response Time:** ' . $responseTime)
            ->line('Please keep your ticket number handy for future reference.')
            ->line('Our support team will follow up with you shortly.');
    }

    public function toArray(object $notifiable): array
    {
        $responseTime = match($this->ticket->priority) {
            'high'   => '2–4 hours',
            'medium' => '12–24 hours',
            default  => '24–48 hours',
        };

        return [
            'title'   => 'Support Ticket #' . $this->ticket->ticket_number . ' Received',
            'message' => 'Your ' . $this->ticket->priority . '-priority ticket has been received. Expected response: ' . $responseTime . '.',
        ];
    }
}
