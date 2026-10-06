<?php

namespace App\Http\Controllers\Vehicule;

use App\Http\Controllers\Controller;
use App\Models\CategorieVehicule;
use App\Models\Vehicule;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class VehiculeController extends Controller
{
    public const STATUTS = [
        'disponible'   => 'Disponible',
        'maintenance'  => 'En maintenance',
        'hors_service' => 'Hors service',
    ];

    public const TRANSMISSIONS = [
        'manuelle'     => 'Manuelle',
        'automatique'  => 'Automatique',
    ];

    public const CARBURANTS = [
        'essence'    => 'Essence',
        'diesel'     => 'Diesel',
        'hybride'    => 'Hybride',
        'electrique' => 'Électrique',
    ];

    private function rules(?int $ignoreId = null): array
    {
        return [
            'categorie_vehicule_id' => ['required', Rule::exists(CategorieVehicule::class, 'id')],
            'marque'                => ['required', 'string', 'max:60'],
            'modele'                => ['required', 'string', 'max:60'],
            'immatriculation'       => ['required', 'string', 'max:20', Rule::unique(Vehicule::class, 'immatriculation')->ignore($ignoreId)],
            'annee'                 => ['nullable', 'integer', 'between:1990,' . (date('Y') + 1)],
            'couleur'               => ['nullable', 'string', 'max:40'],
            'nombre_places'         => ['nullable', 'integer', 'between:1,60'],
            'transmission'          => ['nullable', Rule::in(array_keys(self::TRANSMISSIONS))],
            'carburant'             => ['nullable', Rule::in(array_keys(self::CARBURANTS))],
            'prix_jour'             => ['required', 'numeric', 'min:0'],
            'statut'                => ['required', Rule::in(array_keys(self::STATUTS))],
            'description'           => ['nullable', 'string', 'max:1000'],
        ];
    }

    private function messages(): array
    {
        return [
            'required'                => 'Le champ :attribute est obligatoire.',
            'string'                  => 'Le champ :attribute est invalide.',
            'max'                     => 'Le champ :attribute est trop long (:max caractères maximum).',
            'integer'                 => 'Le champ :attribute doit être un nombre entier.',
            'numeric'                 => 'Le champ :attribute doit être un nombre.',
            'between'                 => 'Le champ :attribute doit être compris entre :min et :max.',
            'min'                     => 'Le champ :attribute doit être supérieur ou égal à :min.',
            'in'                      => 'La valeur choisie pour :attribute est invalide.',
            'exists'                  => 'La :attribute sélectionnée est invalide.',
            'immatriculation.unique'  => 'Cette immatriculation existe déjà.',
        ];
    }

    private function attributes(): array
    {
        return [
            'categorie_vehicule_id' => 'catégorie',
            'immatriculation'       => 'immatriculation',
            'nombre_places'         => 'nombre de places',
            'prix_jour'             => 'prix par jour',
        ];
    }

    public function index()
    {
        return view('admin.location-vehicule.vehicule.vehicule', [
            'vehicules'     => Vehicule::with('categorie')->orderBy('marque')->orderBy('modele')->get(),
            'categories'    => CategorieVehicule::orderBy('nom')->get(),
            'statuts'       => self::STATUTS,
            'transmissions' => self::TRANSMISSIONS,
            'carburants'    => self::CARBURANTS,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate($this->rules(), $this->messages(), $this->attributes());

        try {
            $vehicule = Vehicule::create($validated);
        } catch (\Throwable $e) {
            report($e);

            return back()->withInput()
                ->withErrors(['global' => 'Une erreur est survenue, veuillez réessayer.']);
        }

        return redirect()->route('admin.vehicules.index')
            ->with('success', "Le véhicule « {$vehicule->marque} {$vehicule->modele} » a été ajouté.");
    }

    public function update(Request $request, Vehicule $vehicule)
    {
        $validated = $request->validate($this->rules($vehicule->id), $this->messages(), $this->attributes());

        try {
            $vehicule->update($validated);
        } catch (\Throwable $e) {
            report($e);

            return back()->withInput()
                ->withErrors(['global' => 'Une erreur est survenue, veuillez réessayer.'], 'edit');
        }

        return redirect()->route('admin.vehicules.index')
            ->with('success', "Le véhicule « {$vehicule->marque} {$vehicule->modele} » a été mis à jour.");
    }

    public function destroy(Vehicule $vehicule)
    {
        if ($vehicule->reservations()->exists()) {
            return redirect()->route('admin.vehicules.index')
                ->with('error', 'Impossible de supprimer ce véhicule : il a des réservations. Passez-le plutôt en « Hors service ».');
        }

        try {
            $vehicule->delete();
        } catch (\Throwable $e) {
            report($e);

            return redirect()->route('admin.vehicules.index')
                ->with('error', 'Impossible de supprimer ce véhicule.');
        }

        return redirect()->route('admin.vehicules.index')
            ->with('success', 'Le véhicule a été supprimé.');
    }
}
