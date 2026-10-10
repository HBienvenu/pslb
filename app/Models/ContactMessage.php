<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContactMessage extends Model
{
    use SoftDeletes;

    public const SUJETS = [
        'location'  => 'Location de voiture',
        'livraison' => 'Livraison & courses',
        'media'     => 'Couverture médiatique',
        'autre'     => 'Autre demande',
    ];

    protected $fillable = [
        'nom',
        'telephone',
        'email',
        'sujet',
        'message',
        'est_lu',
        'ip_address',
    ];

    protected $casts = [
        'est_lu' => 'boolean',
    ];

    public function scopeNonLus(Builder $query): Builder
    {
        return $query->where('est_lu', false);
    }
}
