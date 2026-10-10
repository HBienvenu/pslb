<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vehicule extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'categorie_id',
        'nom',
        'immatriculation',
        'description',
        'image',
        'nombre_places',
        'boite_vitesse',
        'carburant',
        'climatisation',
        'prix_jour',
        'est_disponible',
        'ordre',
    ];

    protected $casts = [
        'climatisation' => 'boolean',
        'est_disponible' => 'boolean',
        'prix_jour' => 'integer',
    ];

    public function categorie(): BelongsTo
    {
        return $this->belongsTo(CategorieVehicule::class, 'categorie_id');
    }

    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }

    /** Véhicules visibles sur le site. */
    public function scopeDisponibles(Builder $query): Builder
    {
        return $query->where('est_disponible', true);
    }
}
