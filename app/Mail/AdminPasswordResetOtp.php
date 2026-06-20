<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AdminPasswordResetOtp extends Mailable
{
    use Queueable, SerializesModels;

    public string $otp;
    public string $adminName;

    public function __construct(string $otp, string $adminName)
    {
        $this->otp = $otp;
        $this->adminName = $adminName;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your Admin Password Reset OTP — ' . config('app.name'),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.admin-otp',
        );
    }
}
