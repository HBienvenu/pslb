<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reservation extends Model
{
    public const EN_ATTENTE = 'en_attente';
    public const CONFIRMEE = 'confirmee';
    public const REFUSEE = 'refusee';
    public const ANNULEE = 'annulee';
    public const TERMINEE = 'terminee';

    protected $fillable = [
        'reference', 'vehicule_id', 'nom', 'telephone', 'email', 'avec_chauffeur',
        'date_debut', 'date_fin', 'lieu_prise', 'message',
        'statut', 'note_interne', 'ip_address',
    ];

    protected $casts = [
        'avec_chauffeur' => 'boolean',
        'date_debut' => 'date',
        'date_fin' => 'date',
    ];

    protected static function booted(): void
    {
        // Référence lisible générée après l'insertion : LOC-2026-0042
        static::created(function (self $reservation) {
            $reservation->updateQuietly([
                'reference' => sprintf('LOC-%s-%04d', now()->format('Y'), $reservation->id),
            ]);
        });
    }

    public function vehicule(): BelongsTo
    {
        return $this->belongsTo(Vehicule::class)->withTrashed();
    }

    /** Réservations confirmées dont la période chevauche [début, fin]. */
    public function scopeConfirmeesSur(Builder $query, $debut, $fin): Builder
    {
        return $query->where('statut', self::CONFIRMEE)
            ->where('date_debut', '<=', $fin)
            ->where('date_fin', '>=', $debut);
    }
}
