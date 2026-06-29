<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class WelcomeSubscriptionMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $couponCode;
    public string $lang;
    public array $emailData;

    /**
     * Create a new message instance.
     *
     * @param string $couponCode
     * @param string $lang
     */
    public function __construct(string $couponCode, string $lang = 'en')
    {
        $this->couponCode = $couponCode;
        $this->lang = strtolower($lang);

        $translations = [
            'en' => [
                'subject' => 'Welcome to Urška! Your 10% Discount Code',
                'title' => 'Welcome to Urška!',
                'greeting' => 'Hello!',
                'intro' => 'Thank you for subscribing to our newsletter! As a token of our appreciation, here is a 10% discount code for your first purchase.',
                'coupon_label' => 'YOUR COUPON CODE:',
                'expiry' => 'This coupon code is valid for 30 days and can be used once.',
                'cta' => 'Visit Our Shop',
                'footer' => 'Best regards, The Urška Team',
            ],
            'sl' => [
                'subject' => 'Dobrodošli v Urška! Vaša koda za 10% popust',
                'title' => 'Dobrodošli v Urška!',
                'greeting' => 'Pozdravljeni!',
                'intro' => 'Hvala, ker ste se naročili na naše e-novice! V znak hvaležnosti vam podarjamo kodo za 10% popust pri vašem prvem nakupu.',
                'coupon_label' => 'VAŠA KODA ZA POPUST:',
                'expiry' => 'Koda za popust je veljavna 30 dni in jo je mogoče uporabiti enkrat.',
                'cta' => 'Obiščite našo trgovino',
                'footer' => 'Lep pozdrav, Ekipa Urška',
            ],
            'hr' => [
                'subject' => 'Dobrodošli u Urška! Vaš kupon za 10% popusta',
                'title' => 'Dobrodošli u Urška!',
                'greeting' => 'Pozdrav!',
                'intro' => 'Hvala vam što ste se pretplatili na naš newsletter! U znak zahvalnosti, poklanjamo vam kupon za 10% popusta pri vašoj prvoj kupnji.',
                'coupon_label' => 'VAŠ KUPON KOD:',
                'expiry' => 'Kupon kod vrijedi 30 dana i može se iskoristiti jednom.',
                'cta' => 'Posjetite našu trgovinu',
                'footer' => 'Srdačan pozdrav, Ekipa Urška',
            ],
        ];

        $this->emailData = $translations[$this->lang] ?? $translations['en'];
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
            view: 'emails.welcome-subscription',
        );
    }
}
