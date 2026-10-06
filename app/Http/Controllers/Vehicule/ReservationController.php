<?php

namespace App\Http\Controllers\Vehicule;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Models\Vehicule;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ReservationController extends Controller
{
    public const STATUTS = [
        'en_attente' => 'En attente',
        'confirmee'  => 'Confirmée',
        'en_cours'   => 'En cours',
        'terminee'   => 'Terminée',
        'annulee'    => 'Annulée',
    ];

    /** Statuts qui bloquent le véhicule sur la période. */
    private const ACTIFS = ['en_attente', 'confirmee', 'en_cours'];

    private function rules(bool $creation): array
    {
        return [
            'vehicule_id' => ['required', Rule::exists(Vehicule::class, 'id')],
            'nom_client'  => ['required', 'string', 'max:120'],
            'telephone'   => ['required', 'string', 'max:30'],
            'email'       => ['nullable', 'email', 'max:150'],
            'date_debut'  => $creation
                ? ['required', 'date', 'after_or_equal:today']
                : ['required', 'date'],
            'date_fin'    => ['required', 'date', 'after_or_equal:date_debut'],
            'statut'      => ['required', Rule::in(array_keys(self::STATUTS))],
            'notes'       => ['nullable', 'string', 'max:1000'],
        ];
    }

    private function messages(): array
    {
        return [
            'required'                => 'Le champ :attribute est obligatoire.',
            'string'                  => 'Le champ :attribute est invalide.',
            'max'                     => 'Le champ :attribute est trop long (:max caractères maximum).',
            'email'                   => 'L’adresse e-mail est invalide.',
            'date'                    => 'Le champ :attribute doit être une date valide.',
            'in'                      => 'La valeur choisie pour :attribute est invalide.',
            'exists'                  => 'Le véhicule sélectionné est invalide.',
            'date_debut.after_or_equal' => 'La date de début ne peut pas être dans le passé.',
            'date_fin.after_or_equal'   => 'La date de fin doit être égale ou postérieure à la date de début.',
        ];
    }

    private function attributes(): array
    {
        return [
            'vehicule_id' => 'véhicule',
            'nom_client'  => 'nom du client',
            'telephone'   => 'téléphone',
            'date_debut'  => 'date de début',
            'date_fin'    => 'date de fin',
        ];
    }

    /** Vrai si le véhicule a déjà une réservation active qui chevauche la période. */
    private function chevauche(int $vehiculeId, string $debut, string $fin, ?int $ignoreId = null): bool
    {
        return Reservation::where('vehicule_id', $vehiculeId)
            ->whereIn('statut', self::ACTIFS)
            ->when($ignoreId, fn($q) => $q->where('id', '!=', $ignoreId))
            ->whereDate('date_debut', '<=', $fin)
            ->whereDate('date_fin', '>=', $debut)
            ->exists();
    }

    /** Prix total = nombre de jours (début et fin inclus) × prix par jour. */
    private function total(Vehicule $vehicule, string $debut, string $fin): float
    {
        $jours = (int) Carbon::parse($debut)->startOfDay()->diffInDays(Carbon::parse($fin)->startOfDay()) + 1;

        return $jours * (float) $vehicule->prix_jour;
    }

    public function index(Request $request)
    {
        $filtre = $request->query('statut');
        $filtre = array_key_exists($filtre, self::STATUTS) ? $filtre : null;

        return view('admin.reservations.index', [
            'reservations' => Reservation::with('vehicule')
                ->when($filtre, fn($q) => $q->where('statut', $filtre))
                ->orderByDesc('date_debut')
                ->get(),
            'vehicules'    => Vehicule::orderBy('marque')->orderBy('modele')->get(),
            'counts'       => Reservation::selectRaw('statut, count(*) as total')->groupBy('statut')->pluck('total', 'statut'),
            'statuts'      => self::STATUTS,
            'filtre'       => $filtre,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate($this->rules(true), $this->messages(), $this->attributes());
        $vehicule = Vehicule::findOrFail($validated['vehicule_id']);

        if ($vehicule->statut !== 'disponible') {
            return back()->withInput()
                ->withErrors(['vehicule_id' => 'Ce véhicule n’est pas disponible à la location.']);
        }

        if (
            in_array($validated['statut'], self::ACTIFS, true)
            && $this->chevauche($vehicule->id, $validated['date_debut'], $validated['date_fin'])
        ) {
            return back()->withInput()
                ->withErrors(['date_debut' => 'Ce véhicule est déjà réservé sur cette période.']);
        }

        $validated['prix_total'] = $this->total($vehicule, $validated['date_debut'], $validated['date_fin']);

        try {
            Reservation::create($validated);
        } catch (\Throwable $e) {
            report($e);

            return back()->withInput()
                ->withErrors(['global' => 'Une erreur est survenue, veuillez réessayer.']);
        }

        return redirect()->route('admin.reservations.index')
            ->with('success', "La réservation de « {$validated['nom_client']} » a été créée.");
    }

    public function update(Request $request, Reservation $reservation)
    {
        $validated = $request->validate($this->rules(false), $this->messages(), $this->attributes());
        $vehicule = Vehicule::findOrFail($validated['vehicule_id']);

        // On ne bloque sur le statut du véhicule que s'il a été changé
        if ((int) $validated['vehicule_id'] !== (int) $reservation->vehicule_id && $vehicule->statut !== 'disponible') {
            return back()->withInput()
                ->withErrors(['vehicule_id' => 'Ce véhicule n’est pas disponible à la location.'], 'edit');
        }

        if (
            in_array($validated['statut'], self::ACTIFS, true)
            && $this->chevauche($vehicule->id, $validated['date_debut'], $validated['date_fin'], $reservation->id)
        ) {
            return back()->withInput()
                ->withErrors(['date_debut' => 'Ce véhicule est déjà réservé sur cette période.'], 'edit');
        }

        $validated['prix_total'] = $this->total($vehicule, $validated['date_debut'], $validated['date_fin']);

        try {
            $reservation->update($validated);
        } catch (\Throwable $e) {
            report($e);

            return back()->withInput()
                ->withErrors(['global' => 'Une erreur est survenue, veuillez réessayer.'], 'edit');
        }

        return redirect()->route('admin.reservations.index')
            ->with('success', "La réservation de « {$reservation->nom_client} » a été mise à jour.");
    }

    /** Changement rapide de statut depuis le tableau. */
    public function updateStatut(Request $request, Reservation $reservation)
    {
        $validated = $request->validate([
            'statut' => ['required', Rule::in(array_keys(self::STATUTS))],
        ]);

        $reactivation = in_array($validated['statut'], self::ACTIFS, true)
            && !in_array($reservation->statut, self::ACTIFS, true);

        if ($reactivation && $this->chevauche(
            $reservation->vehicule_id,
            Carbon::parse($reservation->date_debut)->toDateString(),
            Carbon::parse($reservation->date_fin)->toDateString(),
            $reservation->id
        )) {
            return back()->with('error', 'Impossible de réactiver : le véhicule est déjà réservé sur cette période.');
        }

        $reservation->update($validated);

        return back()->with('success', 'Le statut de la réservation a été mis à jour.');
    }

    public function destroy(Reservation $reservation)
    {
        if ($reservation->statut === 'en_cours') {
            return redirect()->route('admin.reservations.index')
                ->with('error', 'Impossible de supprimer une réservation en cours. Terminez-la ou annulez-la d’abord.');
        }

        try {
            $reservation->delete();
        } catch (\Throwable $e) {
            report($e);

            return redirect()->route('admin.reservations.index')
                ->with('error', 'Impossible de supprimer cette réservation.');
        }

        return redirect()->route('admin.reservations.index')
            ->with('success', 'La réservation a été supprimée.');
    }
}
