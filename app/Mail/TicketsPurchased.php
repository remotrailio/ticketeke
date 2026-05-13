<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TicketsPurchased extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly Order $order,
        private readonly string $storagePath,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your tickets for ' . $this->order->event->title,
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'mail.tickets-purchased',
            with: ['order' => $this->order],
        );
    }

    public function attachments(): array
    {
        return [
            Attachment::fromStorage($this->storagePath)
                ->as('tickets-' . $this->order->order_number . '.pdf')
                ->withMime('application/pdf'),
        ];
    }
}
