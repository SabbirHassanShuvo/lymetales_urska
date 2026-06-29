<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\URL;

class OrderConfirmedMail extends Mailable
{
    use Queueable, SerializesModels;

    public Order $order;
    public string $invoiceUrl;

    public function __construct(Order $order)
    {
        $this->order = $order;

        // Generate a signed URL valid for 7 days (no auth required)
        $this->invoiceUrl = URL::signedRoute(
            'orders.invoice.download',
            ['order' => $order->id],
            now()->addDays(7)
        );
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '✅ Order Confirmed #' . $this->order->order_number . ' - ' . config('app.name'),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.order-confirmed',
        );
    }
}
