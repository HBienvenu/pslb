<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ReservationConfirmationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly string $nom,
        public readonly string $telephone,
        public readonly ?string $email,
        public readonly ?string $vehicule,
        public readonly string $dateDebut,
        public readonly string $dateFin,
        public readonly ?string $lieuPrise,
        public readonly ?string $message,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Confirmation de votre demande de réservation - ' . config('app.name'),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.reservation.confirmation',
            with: [
                'nom' => $this->nom,
                'telephone' => $this->telephone,
                'email' => $this->email,
                'vehicule' => $this->vehicule,
                'dateDebut' => $this->dateDebut,
                'dateFin' => $this->dateFin,
                'lieuPrise' => $this->lieuPrise,
                'message' => $this->message,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
