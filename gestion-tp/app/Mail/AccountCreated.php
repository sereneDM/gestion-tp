<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AccountCreated extends Mailable
{
    use Queueable, SerializesModels;

    public $userName;
    public $userEmail;
    public $temporaryPassword;
    public $userRole;
    public $setupUrl;

    /**
     * Create a new message instance.
     */
    public function __construct($userName, $userEmail, $temporaryPassword, $userRole, $token)
    {
        $this->userName = $userName;
        $this->userEmail = $userEmail;
        $this->temporaryPassword = $temporaryPassword;
        $this->userRole = $userRole;
        $this->setupUrl = route('password.setup', ['token' => $token, 'email' => $userEmail]);
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Bienvenue - Configurez votre compte',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.account-created',
        );
    }

    /**
     * Get the attachments for the message.
     */
    public function attachments(): array
    {
        return [];
    }
}