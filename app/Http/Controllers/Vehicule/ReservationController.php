<?php

namespace App\Http\Controllers\Vehicule;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Models\Vehicule;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ReservationController extends Controller
{
    public const STATUTS = [
        Reservation::EN_ATTENTE => 'En attente',
        Reservation::CONFIRMEE  => 'Confirmée',
        Reservation::REFUSEE    => 'Refusée',
        Reservation::ANNULEE    => 'Annulée',
        Reservation::TERMINEE   => 'Terminée',
    ];

    private function rules(bool $creation = true): array
    {
        return [
            'nom'          => ['required', 'string', 'max:255'],
            'telephone'    => ['required', 'string', 'max:30'],
            'email'        => ['nullable', 'email', 'max:255'],
            'vehicule_id'  => ['nullable', Rule::exists(Vehicule::class, 'id')->whereNull('deleted_at')],
            'date_debut'   => $creation
                ? ['required', 'date', 'after_or_equal:today']
                : ['required', 'date'],
            'date_fin'     => ['required', 'date', 'after_or_equal:date_debut'],
            'lieu_prise'   => ['nullable', 'string', 'max:255'],
            'statut'       => ['required', Rule::in(array_keys(self::STATUTS))],
            'message'      => ['nullable', 'string', 'max:2000'],
            'note_interne' => ['nullable', 'string', 'max:2000'],
        ];
    }

    private function messages(): array
    {
        return [
            'required'                  => 'Le champ :attribute est obligatoire.',
            'string'                    => 'Le champ :attribute est invalide.',
            'max'                       => 'Le champ :attribute est trop long (:max caractères maximum).',
            'email'                     => 'L’adresse e-mail est invalide.',
            'date'                      => 'Le champ :attribute doit être une date valide.',
            'in'                        => 'La valeur choisie pour :attribute est invalide.',
            'exists'                    => 'Le véhicule sélectionné est invalide.',
            'date_debut.after_or_equal' => 'La date de début ne peut pas être dans le passé.',
            'date_fin.after_or_equal'   => 'La date de fin doit être égale ou postérieure à la date de début.',
        ];
    }

    private function attributes(): array
    {
        return [
            'nom'          => 'nom du client',
            'telephone'    => 'téléphone',
            'vehicule_id'  => 'véhicule',
            'date_debut'   => 'date de début',
            'date_fin'     => 'date de fin',
            'lieu_prise'   => 'lieu de prise en charge',
            'note_interne' => 'note interne',
        ];
    }

    /**
     * Règles métier avant d'enregistrer. Retourne un tableau d'erreurs, ou null si tout est OK.
     * Seules les réservations CONFIRMÉES bloquent un véhicule (comme sur le site public).
     */
    private function verifier(array $data, ?int $ignoreId = null): ?array
    {
        if ($data['statut'] !== Reservation::CONFIRMEE) {
            return null;
        }

        if (empty($data['vehicule_id'])) {
            return ['vehicule_id' => 'Choisissez un véhicule pour confirmer la réservation.'];
        }

        $conflit = Reservation::where('vehicule_id', $data['vehicule_id'])
            ->confirmeesSur($data['date_debut'], $data['date_fin'])
            ->when($ignoreId, fn($q) => $q->where('id', '!=', $ignoreId))
            ->exists();

        return $conflit
            ? ['vehicule_id' => 'Ce véhicule a déjà une réservation confirmée sur cette période.']
            : null;
    }

    public function index(Request $request)
    {
        $filtre = $request->query('statut');
        $filtre = array_key_exists((string) $filtre, self::STATUTS) ? $filtre : null;

        return view('admin.location-vehicule.reservation-vehicule.reservation-vehicule', [
            'reservations' => Reservation::with('vehicule')
                ->when($filtre, fn($q) => $q->where('statut', $filtre))
                ->orderByDesc('date_debut')
                ->orderByDesc('id')
                ->get(),
            'vehicules'    => Vehicule::orderBy('nom')->get(),
            'counts'       => Reservation::selectRaw('statut, count(*) as total')->groupBy('statut')->pluck('total', 'statut'),
            'statuts'      => self::STATUTS,
            'filtre'       => $filtre,
        ]);
    }

    /** Saisie manuelle d'une réservation (téléphone, comptoir…). */
    public function store(Request $request)
    {
        $data = $request->validate($this->rules(), $this->messages(), $this->attributes());
        $data['avec_chauffeur'] = $request->boolean('avec_chauffeur');

        if ($erreurs = $this->verifier($data)) {
            return back()->withInput()->withErrors($erreurs);
        }

        try {
            $reservation = Reservation::create($data); // la référence est générée par le modèle
        } catch (\Throwable $e) {
            report($e);

            return back()->withInput()
                ->withErrors(['global' => 'Une erreur est survenue, veuillez réessayer.']);
        }

        return redirect()->route('admin.reservations.index')
            ->with('success', "La réservation {$reservation->fresh()->reference} de « {$reservation->nom} » a été créée.");
    }

    /**
     * Modification des informations de la demande.
     * Le statut et la note interne ne sont PAS modifiés ici (voir updateStatut).
     */
    public function update(Request $request, Reservation $reservation)
    {
        $rules = $this->rules(false);
        unset($rules['statut'], $rules['note_interne']);

        $data = $request->validate($rules, $this->messages(), $this->attributes());
        $data['avec_chauffeur'] = $request->boolean('avec_chauffeur');

        // Si la réservation est confirmée, on revérifie le véhicule et les dates
        if ($erreurs = $this->verifier($data + ['statut' => $reservation->statut], $reservation->id)) {
            return back()->withInput()->withErrors($erreurs, 'edit');
        }

        try {
            $reservation->update($data);
        } catch (\Throwable $e) {
            report($e);

            return back()->withInput()
                ->withErrors(['global' => 'Une erreur est survenue, veuillez réessayer.'], 'edit');
        }

        return redirect()->route('admin.reservations.index')
            ->with('success', "La réservation {$reservation->reference} de « {$reservation->nom} » a été mise à jour.");
    }

    /**
     * Changement de statut (modal) : statut + véhicule attribué + note interne.
     * Pour confirmer, un véhicule libre sur la période est obligatoire.
     */
    public function updateStatut(Request $request, Reservation $reservation)
    {
        $data = $request->validate([
            'statut'       => ['required', Rule::in(array_keys(self::STATUTS))],
            'vehicule_id'  => ['nullable', Rule::exists(Vehicule::class, 'id')->whereNull('deleted_at')],
            'note_interne' => ['nullable', 'string', 'max:2000'],
        ], $this->messages(), $this->attributes());

        $vehiculeId = $data['vehicule_id'] ?? null;

        $erreurs = $this->verifier([
            'statut'      => $data['statut'],
            'vehicule_id' => $vehiculeId,
            'date_debut'  => $reservation->date_debut->toDateString(),
            'date_fin'    => $reservation->date_fin->toDateString(),
        ], $reservation->id);

        if ($erreurs) {
            return back()->withInput()->withErrors($erreurs, 'edit');
        }

        try {
            $reservation->update([
                'statut'       => $data['statut'],
                'vehicule_id'  => $vehiculeId,
                'note_interne' => $data['note_interne'] ?? null,
            ]);
        } catch (\Throwable $e) {
            report($e);

            return back()->withInput()
                ->withErrors(['global' => 'Une erreur est survenue, veuillez réessayer.'], 'edit');
        }

        return back()->with('success', "La réservation {$reservation->reference} est maintenant « " . self::STATUTS[$data['statut']] . ' ».');
    }

    public function destroy(Reservation $reservation)
    {
        if ($reservation->statut === Reservation::CONFIRMEE && $reservation->date_fin->gte(today())) {
            return redirect()->route('admin.reservations.index')
                ->with('error', 'Impossible de supprimer une réservation confirmée à venir. Annulez-la d’abord.');
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
