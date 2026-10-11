<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class LivraisonConfirmationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly string $nom,
        public readonly string $telephone,
        public readonly ?string $email,
        public readonly string $villeDepart,
        public readonly string $adresseDepart,
        public readonly string $villeArrivee,
        public readonly string $adresseLivraison,
        public readonly string $typeColis,
        public readonly ?string $poidsKg,
        public readonly string $dateLivraison,
        public readonly ?string $heureLivraison,
        public readonly ?string $description,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Confirmation de votre demande de livraison - ' . config('app.name'),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.livraison.confirmation',
            with: [
                'nom' => $this->nom,
                'telephone' => $this->telephone,
                'email' => $this->email,
                'villeDepart' => $this->villeDepart,
                'adresseDepart' => $this->adresseDepart,
                'villeArrivee' => $this->villeArrivee,
                'adresseLivraison' => $this->adresseLivraison,
                'typeColis' => $this->typeColis,
                'poidsKg' => $this->poidsKg,
                'dateLivraison' => $this->dateLivraison,
                'heureLivraison' => $this->heureLivraison,
                'description' => $this->description,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
