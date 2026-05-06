<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class EmailChangeVerification extends Mailable
{
    use Queueable, SerializesModels;

    public string $userName;
    public string $newEmail;
    public string $code;

    public function __construct(string $userName, string $newEmail, string $code)
    {
        $this->userName = $userName;
        $this->newEmail = $newEmail;
        $this->code     = $code;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Confirmation de changement d\'email',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.email-change-verification',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}