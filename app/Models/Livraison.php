<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Livraison extends Model
{
    use SoftDeletes;

    public const EN_ATTENTE = 'en_attente';
    public const CONFIRMEE = 'confirmee';
    public const EN_COURS = 'en_cours';
    public const LIVREE = 'livree';
    public const REFUSEE = 'refusee';

    protected $fillable = [
        'reference',
        'nom',
        'telephone',
        'email',
        'ville_depart',
        'adresse_depart',
        'ville_arrivee',
        'adresse_livraison',
        'type_colis',
        'poids_kg',
        'description',
        'date_livraison',
        'heure_livraison',
        'image_colis',
        'statut',
        'note_interne',
        'ip_address',
    ];

    protected $casts = [
        'date_livraison' => 'date',
        'poids_kg' => 'decimal:2',
    ];

    protected static function booted(): void
    {
        static::created(function (self $livraison) {
            $livraison->updateQuietly([
                'reference' => sprintf('LIV-%s-%04d', now()->format('Y'), $livraison->id),
            ]);
        });
    }

    public function scopeEnAttente(Builder $query): Builder
    {
        return $query->where('statut', self::EN_ATTENTE);
    }
}
