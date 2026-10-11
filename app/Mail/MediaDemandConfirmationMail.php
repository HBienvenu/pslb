<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class MediaDemandConfirmationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly string $nom,
        public readonly string $telephone,
        public readonly ?string $email,
        public readonly ?string $organisation,
        public readonly string $titreEvenement,
        public readonly ?string $lieu,
        public readonly string $dateDebut,
        public readonly ?string $dateFin,
        public readonly array $services,
        public readonly ?string $message,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Confirmation de votre demande média - ' . config('app.name'),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.media.confirmation',
            with: [
                'nom' => $this->nom,
                'telephone' => $this->telephone,
                'email' => $this->email,
                'organisation' => $this->organisation,
                'titreEvenement' => $this->titreEvenement,
                'lieu' => $this->lieu,
                'dateDebut' => $this->dateDebut,
                'dateFin' => $this->dateFin,
                'services' => $this->services,
                'message' => $this->message,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
