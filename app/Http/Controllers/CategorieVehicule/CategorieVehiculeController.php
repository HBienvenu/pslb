<?php

namespace App\Http\Controllers\CategorieVehicule;

use App\Http\Controllers\Controller;
use App\Models\CategorieVehicule;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CategorieVehiculeController extends Controller
{
    private function messages(): array
    {
        return [
            'nom.required'        => 'Le nom de la catégorie est obligatoire.',
            'nom.max'             => 'Le nom ne doit pas dépasser 80 caractères.',
            'nom.unique'          => 'Cette catégorie existe déjà.',
            'description.max'     => 'La description ne doit pas dépasser 255 caractères.',
        ];
    }

    public function index()
    {
        return view('admin.location-vehicule.categorie-vehicule.categorie-vehicule', [
            'categories' => CategorieVehicule::withCount('vehicules')->orderBy('nom')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom'         => ['required', 'string', 'max:80', Rule::unique(CategorieVehicule::class, 'nom')],
            'description' => ['nullable', 'string', 'max:255'],
        ], $this->messages());

        try {
            CategorieVehicule::create($validated);
        } catch (\Throwable $e) {
            report($e);

            return back()->withInput()
                ->withErrors(['global' => 'Une erreur est survenue, veuillez réessayer.']);
        }

        return redirect()->route('admin.categories-vehicule.index')
            ->with('success', "La catégorie « {$validated['nom']} » a été créée.");
    }

    public function update(Request $request, CategorieVehicule $categorieVehicule)
    {
        $validated = $request->validate([
            'nom'         => ['required', 'string', 'max:80', Rule::unique(CategorieVehicule::class, 'nom')->ignore($categorieVehicule->id)],
            'description' => ['nullable', 'string', 'max:255'],
        ], $this->messages());

        try {
            $categorieVehicule->update($validated);
        } catch (\Throwable $e) {
            report($e);

            return back()->withInput()
                ->withErrors(['global' => 'Une erreur est survenue, veuillez réessayer.'], 'edit');
        }

        return redirect()->route('admin.categories-vehicule.index')
            ->with('success', "La catégorie « {$categorieVehicule->nom} » a été mise à jour.");
    }

    public function destroy(CategorieVehicule $categorieVehicule)
    {
        if ($categorieVehicule->vehicules()->exists()) {
            return redirect()->route('admin.categories-vehicule.index')
                ->with('error', 'Impossible de supprimer cette catégorie : des véhicules y sont rattachés.');
        }

        try {
            $categorieVehicule->delete();
        } catch (\Throwable $e) {
            report($e);

            return redirect()->route('admin.categories-vehicule.index')
                ->with('error', 'Impossible de supprimer cette catégorie.');
        }

        return redirect()->route('admin.categories-vehicule.index')
            ->with('success', 'La catégorie a été supprimée.');
    }
}
