<?php

namespace App\Http\Controllers\Vehicule;

use App\Http\Controllers\Controller;
use App\Models\CategorieVehicule;
use App\Models\Vehicule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class VehiculeController extends Controller
{
    public const BOITES = ['Manuelle', 'Automatique'];

    public const CARBURANTS = ['Essence', 'Diesel', 'Hybride', 'Électrique'];

    private function rules(?int $ignoreId = null): array
    {
        return [
            'categorie_id'    => ['nullable', Rule::exists(CategorieVehicule::class, 'id')],
            'nom'             => ['required', 'string', 'max:255'],
            'immatriculation' => ['nullable', 'string', 'max:255', Rule::unique(Vehicule::class, 'immatriculation')->ignore($ignoreId)],
            'description'     => ['nullable', 'string', 'max:2000'],
            'image'           => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:3072'],
            'nombre_places'   => ['nullable', 'integer', 'between:1,60'],
            'boite_vitesse'   => ['nullable', Rule::in(self::BOITES)],
            'carburant'       => ['nullable', Rule::in(self::CARBURANTS)],
            'prix_jour'       => ['nullable', 'integer', 'min:0'],
            'ordre'           => ['nullable', 'integer', 'between:0,65535'],
        ];
    }

    private function messages(): array
    {
        return [
            'required'                => 'Le champ :attribute est obligatoire.',
            'string'                  => 'Le champ :attribute est invalide.',
            'max'                     => 'Le champ :attribute est trop long (:max maximum).',
            'integer'                 => 'Le champ :attribute doit être un nombre entier.',
            'between'                 => 'Le champ :attribute doit être compris entre :min et :max.',
            'min'                     => 'Le champ :attribute doit être supérieur ou égal à :min.',
            'in'                      => 'La valeur choisie pour :attribute est invalide.',
            'exists'                  => 'La :attribute sélectionnée est invalide.',
            'immatriculation.unique'  => 'Cette immatriculation existe déjà.',
            'image.image'             => 'Le fichier doit être une image.',
            'image.mimes'             => 'L’image doit être au format JPG, PNG ou WebP.',
            'image.max'               => 'L’image ne doit pas dépasser 3 Mo.',
        ];
    }

    private function attributes(): array
    {
        return [
            'categorie_id'  => 'catégorie',
            'nombre_places' => 'nombre de places',
            'boite_vitesse' => 'boîte de vitesse',
            'prix_jour'     => 'prix par jour',
        ];
    }

    public function index()
    {
        return view('admin.location-vehicule.vehicule.vehicule', [
            'vehicules'   => Vehicule::with('categorie')->orderBy('ordre')->orderBy('nom')->get(),
            'categories'  => CategorieVehicule::orderBy('nom')->get(),
            'boites'      => self::BOITES,
            'carburants'  => self::CARBURANTS,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate($this->rules(), $this->messages(), $this->attributes());

        // Les cases à cocher n'envoient rien quand elles sont décochées
        $data['climatisation']  = $request->boolean('climatisation');
        $data['est_disponible'] = $request->boolean('est_disponible');
        $data['ordre']          = $data['ordre'] ?? 0;

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('vehicules', 'public');
        } else {
            unset($data['image']);
        }

        try {
            $vehicule = Vehicule::create($data);
        } catch (\Throwable $e) {
            report($e);

            return back()->withInput()
                ->withErrors(['global' => 'Une erreur est survenue, veuillez réessayer.']);
        }

        return redirect()->route('admin.vehicules.index')
            ->with('success', "Le véhicule « {$vehicule->nom} » a été ajouté.");
    }

    public function update(Request $request, Vehicule $vehicule)
    {
        $data = $request->validate($this->rules($vehicule->id), $this->messages(), $this->attributes());

        $data['climatisation']  = $request->boolean('climatisation');
        $data['est_disponible'] = $request->boolean('est_disponible');
        $data['ordre']          = $data['ordre'] ?? 0;

        // Nouvelle image : on remplace l'ancienne. Sinon on garde l'image actuelle.
        if ($request->hasFile('image')) {
            if ($vehicule->image) {
                Storage::disk('public')->delete($vehicule->image);
            }
            $data['image'] = $request->file('image')->store('vehicules', 'public');
        } else {
            unset($data['image']);
        }

        try {
            $vehicule->update($data);
        } catch (\Throwable $e) {
            report($e);

            return back()->withInput()
                ->withErrors(['global' => 'Une erreur est survenue, veuillez réessayer.'], 'edit');
        }

        return redirect()->route('admin.vehicules.index')
            ->with('success', "Le véhicule « {$vehicule->nom} » a été mis à jour.");
    }

    public function destroy(Vehicule $vehicule)
    {
        // On ne retire pas un véhicule qui a des réservations à venir
        $aVenir = $vehicule->reservations()
            ->whereIn('statut', ['en_attente', 'confirmee'])
            ->whereDate('date_fin', '>=', today())
            ->exists();

        if ($aVenir) {
            return redirect()->route('admin.vehicules.index')
                ->with('error', 'Impossible de supprimer ce véhicule : il a des réservations à venir. Masquez-le plutôt du site.');
        }

        try {
            $vehicule->delete(); // softDeletes : l'historique des réservations est conservé
        } catch (\Throwable $e) {
            report($e);

            return redirect()->route('admin.vehicules.index')
                ->with('error', 'Impossible de supprimer ce véhicule.');
        }

        return redirect()->route('admin.vehicules.index')
            ->with('success', 'Le véhicule a été supprimé.');
    }
}
