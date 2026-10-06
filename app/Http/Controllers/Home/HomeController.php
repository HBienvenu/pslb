<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Models\CategorieVehicule;
use App\Models\Reservation;
use App\Models\Vehicule;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function accueil()
    {
        return view('welcome');
    }

    public function location()
    {
        // Véhicules visibles sur le site (est_disponible), triés par « ordre » puis par nom.
        // Les véhicules supprimés (softDeletes) sont exclus automatiquement.
        $vehicules = Vehicule::with('categorie')
            ->where('est_disponible', true)
            ->orderBy('ordre')
            ->orderBy('nom')
            ->get();

        // Uniquement les catégories qui ont au moins un véhicule affiché (évite les filtres vides)
        $categories = CategorieVehicule::whereIn('id', $vehicules->pluck('categorie_id')->filter()->unique())
            ->orderBy('nom')
            ->get();

        return view('site.locations.location', compact('vehicules', 'categories'));
    }

    // Enregistre la demande de réservation envoyée depuis le formulaire public. La demande est créée avec le statut « en_attente » : l'admin la confirme ensuite.
    public function reserver(Request $request)
    {
        if ($request->filled('website')) {
            return back()->with('success', 'Votre demande a bien été envoyée.');
        }

        $validated = $request->validate([
            'nom'            => ['required', 'string', 'max:255'],
            'telephone'      => ['required', 'string', 'max:30'],
            'email'          => ['nullable', 'email', 'max:255'],
            'vehicule_id'    => ['nullable', 'exists:vehicules,id'],
            'avec_chauffeur' => ['required', 'in:0,1'],
            'date_debut'     => ['required', 'date', 'after_or_equal:today'],
            'date_fin'       => ['required', 'date', 'after_or_equal:date_debut'],
            'lieu_prise'     => ['nullable', 'string', 'max:255'],
            'message'        => ['nullable', 'string', 'max:2000'],
            'captcha'        => ['required', 'numeric'],
        ], [
            'required'                  => 'Le champ :attribute est obligatoire.',
            'max'                       => 'Le champ :attribute est trop long (:max caractères maximum).',
            'email'                     => 'L’adresse e-mail est invalide.',
            'exists'                    => 'Le véhicule sélectionné est invalide.',
            'date'                      => 'Le champ :attribute doit être une date valide.',
            'numeric'                   => 'La réponse de vérification doit être un nombre.',
            'date_debut.after_or_equal' => 'La date de début ne peut pas être dans le passé.',
            'date_fin.after_or_equal'   => 'La date de fin doit être égale ou postérieure à la date de début.',
        ], [
            'nom'        => 'nom complet',
            'telephone'  => 'téléphone',
            'date_debut' => 'date de début',
            'date_fin'   => 'date de fin',
            'lieu_prise' => 'lieu de prise en charge',
        ]);

        // Vérification anti-robot (valeurs générées dans la vue et stockées en session)
        $attendu = (int) session('loc_captcha_a') + (int) session('loc_captcha_b');

        if (!session()->has('loc_captcha_a') || (int) $validated['captcha'] !== $attendu) {
            return back()->withInput()
                ->withErrors(['captcha' => 'La réponse à la vérification anti-robot est incorrecte.']);
        }

        // Véhicule choisi : il doit exister (non supprimé), être visible et libre sur la période
        $vehicule = null;

        if (!empty($validated['vehicule_id'])) {
            $vehicule = Vehicule::find($validated['vehicule_id']);

            if (!$vehicule || !$vehicule->est_disponible) {
                return back()->withInput()
                    ->withErrors(['vehicule_id' => 'Ce véhicule n’est pas disponible à la location.']);
            }

            // Seules les réservations déjà confirmées bloquent le véhicule :
            // de simples demandes « en attente » ne doivent pas pouvoir l'immobiliser.
            $occupe = Reservation::where('vehicule_id', $vehicule->id)
                ->where('statut', 'confirmee')
                ->whereDate('date_debut', '<=', $validated['date_fin'])
                ->whereDate('date_fin', '>=', $validated['date_debut'])
                ->exists();

            if ($occupe) {
                return back()->withInput()
                    ->withErrors(['date_debut' => 'Ce véhicule est déjà réservé sur cette période. Choisissez d’autres dates ou un autre véhicule.']);
            }
        }

        try {
            DB::transaction(function () use ($validated, $vehicule, $request) {
                $reservation = Reservation::create([
                    'vehicule_id'    => $vehicule?->id,
                    'nom'            => $validated['nom'],
                    'telephone'      => $validated['telephone'],
                    'email'          => $validated['email'] ?? null,
                    'avec_chauffeur' => (bool) $validated['avec_chauffeur'],
                    'date_debut'     => $validated['date_debut'],
                    'date_fin'       => $validated['date_fin'],
                    'lieu_prise'     => $validated['lieu_prise'] ?? null,
                    'message'        => $validated['message'] ?? null,
                    'statut'         => 'en_attente',
                    'ip_address'     => $request->ip(),
                ]);

                // Référence lisible, ex : LOC-2026-0042 (basée sur l'id => toujours unique)
                $reservation->update([
                    'reference' => sprintf('LOC-%d-%04d', now()->year, $reservation->id),
                ]);
            });
        } catch (\Throwable $e) {
            report($e);

            return back()->withInput()
                ->withErrors(['global' => 'Une erreur est survenue, veuillez réessayer.']);
        }

        // Nouveau calcul anti-robot au prochain affichage
        session()->forget(['loc_captcha_a', 'loc_captcha_b']);

        return back()->with('success', 'Votre demande a bien été envoyée. Nous vous recontactons très vite pour la confirmer.');
    }

    public function livraison() {}

    public function media() {}

    public function aPropos() {}

    public function contact() {}
}
