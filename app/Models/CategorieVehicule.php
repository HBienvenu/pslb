<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CategorieVehicule extends Model
{
    protected $fillable = ['nom', 'description', 'ordre'];

    public function vehicules(): HasMany
    {
        return $this->hasMany(Vehicule::class, 'categorie_id');
    }
}
