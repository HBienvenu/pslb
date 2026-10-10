<?php

namespace App\Http\Controllers\Livraison;

use App\Http\Controllers\Controller;
use App\Models\Livraison;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class LivraisonController extends Controller
{
    public const STATUTS = [
        Livraison::EN_ATTENTE => 'En attente',
        Livraison::CONFIRMEE => 'Confirmée',
        Livraison::EN_COURS => 'En cours',
        Livraison::LIVREE => 'Livrée',
        Livraison::REFUSEE => 'Refusée',
    ];

    public function index(Request $request)
    {
        $filtre = $request->query('statut');
        $filtre = array_key_exists((string) $filtre, self::STATUTS) ? $filtre : null;

        return view('admin.livraison.livraison', [
            'livraisons' => Livraison::when($filtre, fn ($query) => $query->where('statut', $filtre))
                ->orderByDesc('date_livraison')
                ->orderByDesc('id')
                ->get(),
            'counts' => Livraison::selectRaw('statut, count(*) as total')
                ->groupBy('statut')
                ->pluck('total', 'statut'),
            'statuts' => self::STATUTS,
            'filtre' => $filtre,
        ]);
    }

    public function updateStatut(Request $request, Livraison $livraison)
    {
        $validated = $request->validate([
            'statut' => ['required', Rule::in(array_keys(self::STATUTS))],
            'note_interne' => ['nullable', 'string', 'max:2000'],
        ], [
            'required' => 'Le champ :attribute est obligatoire.',
            'string' => 'Le champ :attribute est invalide.',
            'max' => 'Le champ :attribute est trop long (:max caractères maximum).',
            'in' => 'La valeur choisie pour :attribute est invalide.',
        ], [
            'statut' => 'statut',
            'note_interne' => 'note interne',
        ]);

        $livraison->update([
            'statut' => $validated['statut'],
            'note_interne' => $validated['note_interne'] ?? null,
        ]);

        return back()->with('success', 'Le statut de la livraison a été mis à jour.');
    }

    public function destroy(Livraison $livraison)
    {
        $livraison->delete();

        return redirect()->route('admin.livraisons.index')
            ->with('success', 'La demande de livraison a été supprimée.');
    }
}
