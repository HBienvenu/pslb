<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactConfirmationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly string $nom,
        public readonly string $telephone,
        public readonly ?string $email,
        public readonly string $sujet,
        public readonly string $message,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Confirmation de votre demande - ' . config('app.name'),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.contact.confirmation',
            with: [
                'nom' => $this->nom,
                'telephone' => $this->telephone,
                'email' => $this->email,
                'sujet' => $this->sujet,
                'message' => $this->message,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
