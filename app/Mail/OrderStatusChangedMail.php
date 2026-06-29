<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderStatusChangedMail extends Mailable
{
    use Queueable, SerializesModels;

    public Order $order;
    public string $status;
    public string $lang;
    public array $emailData;

    /**
     * Create a new message instance.
     *
     * @param Order $order
     * @param string $status
     */
    public function __construct(Order $order, string $status)
    {
        $this->order = $order;
        $this->status = strtolower($status);
        
        // Detect language from order source/domain/locale or default to SL (as standard in this app)
        // Check if order has a language/locale field, otherwise default to SL
        $this->lang = strtolower($order->language ?? 'sl');

        $translations = [
            'en' => [
                'processing' => [
                    'subject' => 'Your Order #' . $order->order_number . ' is now being processed!',
                    'badge' => '⚙️ Processing',
                    'title' => 'Order is being processed!',
                    'message' => 'Great news! We have started processing your order. Our team is preparing your custom book with the utmost care.',
                ],
                'delivered' => [
                    'subject' => 'Your Order #' . $order->order_number . ' has been delivered!',
                    'badge' => '🏠 Delivered',
                    'title' => 'Order Delivered!',
                    'message' => 'Your package has been successfully delivered. We hope your little one enjoys their new personalised book!',
                ],
                'cancelled' => [
                    'subject' => 'Your Order #' . $order->order_number . ' has been cancelled',
                    'badge' => '❌ Cancelled',
                    'title' => 'Order Cancelled',
                    'message' => 'Your order has been cancelled. If you have any questions or did not request this, please reply directly to this email.',
                ],
            ],
            'sl' => [
                'processing' => [
                    'subject' => 'Vaše naročilo #' . $order->order_number . ' se sedaj obdeluje!',
                    'badge' => '⚙️ V obdelavi',
                    'title' => 'Naročilo je v obdelavi!',
                    'message' => 'Odlične novice! Začeli smo z obdelavo vašega naročila. Naša ekipa z ljubeznijo pripravlja vašo unikatno knjigo.',
                ],
                'delivered' => [
                    'subject' => 'Vaše naročilo #' . $order->order_number . ' je bilo dostavljeno!',
                    'badge' => '🏠 Dostavljeno',
                    'title' => 'Naročilo dostavljeno!',
                    'message' => 'Vaš paket je bil uspešno dostavljen. Upamo, da bo vaš malček užival v branju svoje nove osebne knjige!',
                ],
                'cancelled' => [
                    'subject' => 'Vaše naročilo #' . $order->order_number . ' je bilo preklicano',
                    'badge' => '❌ Preklicano',
                    'title' => 'Naročilo preklicano',
                    'message' => 'Vaše naročilo je bilo preklicano. Če imate kakršna koli vprašanja, nam odgovorite neposredno na to e-pošto.',
                ],
            ],
            'hr' => [
                'processing' => [
                    'subject' => 'Vaša narudžba #' . $order->order_number . ' se sada obrađuje!',
                    'badge' => '⚙️ U obradi',
                    'title' => 'Narudžba se obrađuje!',
                    'message' => 'Odlične vijesti! Započeli smo s obradom vaše narudžbe. Naš tim s ljubavlju priprema vašu personaliziranu knjigu.',
                ],
                'delivered' => [
                    'subject' => 'Vaša narudžba #' . $order->order_number . ' je dostavljena!',
                    'badge' => '🏠 Dostavljeno',
                    'title' => 'Narudžba dostavljena!',
                    'message' => 'Vaš paket je uspješno dostavljen. Nadamo se da će vaš mališan uživati u svojoj novoj personaliziranoj knjizi!',
                ],
                'cancelled' => [
                    'subject' => 'Vaša narudžba #' . $order->order_number . ' je otkazana',
                    'badge' => '❌ Otkazano',
                    'title' => 'Narudžba otkazana',
                    'message' => 'Vaša narudžba je otkazana. Ako imate pitanja, slobodno odgovorite izravno na ovu e-poštu.',
                ],
            ],
        ];

        // Retrieve translations for the detected language or fallback to English
        $langData = $translations[$this->lang] ?? $translations['sl'] ?? $translations['en'];
        $this->emailData = $langData[$this->status] ?? ($translations['en'][$this->status] ?? [
            'subject' => 'Update on Order #' . $order->order_number,
            'badge' => '📋 Update',
            'title' => 'Order Update',
            'message' => 'Your order status has been updated to ' . $status . '.',
        ]);
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->emailData['subject'],
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.order-status-changed',
        );
    }
}
