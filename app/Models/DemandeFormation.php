<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DemandeFormation extends Model
{
    use SoftDeletes;

    protected $table = 'demandes_formation';

    public const EN_ATTENTE = 'en_attente';
    public const EN_COURS = 'en_cours';
    public const TRAITE = 'traite';
    public const VALIDE = 'valide';
    public const REFUSE = 'refuse';

    public const SERVICES = [
        'site' => 'Création de site web',
        'social' => 'Gestion des réseaux sociaux',
        'design' => 'Design graphique & infographie',
        'formation' => 'Formation digitale',
        'assistance' => 'Assistance informatique',
        'autre' => 'Autre besoin',
    ];

    protected $fillable = [
        'nom',
        'telephone',
        'email',
        'service',
        'budget',
        'date_prevue',
        'description',
        'statut',
        'note_interne',
        'ip_address',
    ];

    protected $casts = [
        'date_prevue' => 'date',
        'budget' => 'decimal:2',
    ];

    protected static function booted(): void
    {
        static::created(function (self $demande) {
            $demande->updateQuietly([
                'reference' => sprintf('DIG-%s-%04d', now()->format('Y'), $demande->id),
            ]);
        });
    }

    public function scopeEnAttente(Builder $query): Builder
    {
        return $query->where('statut', self::EN_ATTENTE);
    }
}
