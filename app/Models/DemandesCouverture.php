<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DemandesCouverture extends Model
{
    protected $table = 'demandes_couvertures';

    public const EN_ATTENTE = 'en_attente';
    public const CONFIRMEE = 'confirmee';
    public const REFUSEE = 'refusee';
    public const ANNULEE = 'annulee';
    public const TERMINEE = 'terminee';

    /** Services proposés : clé => libellé */
    public const SERVICES = [
        'photo'     => 'Photos',
        'video'     => 'Vidéos',
        'interview' => 'Interviews',
        'podcast'   => 'Podcasts',
    ];

    protected $fillable = [
        'reference', 'nom', 'telephone', 'email', 'organisation',
        'titre_evenement', 'lieu', 'date_debut', 'date_fin', 'services', 'message',
        'statut', 'montant', 'note_interne', 'ip_address',
    ];

    protected $casts = [
        'services'   => 'array',
        'date_debut' => 'date',
        'date_fin'   => 'date',
        'montant'    => 'integer',
    ];

    protected static function booted(): void
    {
        // Référence lisible générée après l'insertion : MED-2026-0042
        static::created(function (self $demande) {
            $demande->updateQuietly([
                'reference' => sprintf('MED-%s-%04d', now()->format('Y'), $demande->id),
            ]);
        });
    }

    /** Libellés des services demandés, ex : "Photos, Vidéos". */
    public function servicesLabel(): string
    {
        return collect($this->services ?? [])
            ->map(fn($s) => self::SERVICES[$s] ?? $s)
            ->implode(', ');
    }
}
