<?php

namespace App\Http\Controllers\CouvertureMediatique;

use App\Http\Controllers\Controller;
use App\Models\DemandesCouverture;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CouvertureMediatiqueController extends Controller
{
    public const STATUTS = [
        DemandesCouverture::EN_ATTENTE => 'En attente',
        DemandesCouverture::CONFIRMEE  => 'Confirmée',
        DemandesCouverture::REFUSEE    => 'Refusée',
        DemandesCouverture::ANNULEE    => 'Annulée',
        DemandesCouverture::TERMINEE   => 'Terminée',
    ];

    private function rules(bool $creation = true): array
    {
        return [
            'nom'             => ['required', 'string', 'max:255'],
            'telephone'       => ['required', 'string', 'max:30'],
            'email'           => ['nullable', 'email', 'max:255'],
            'organisation'    => ['nullable', 'string', 'max:255'],
            'titre_evenement' => ['required', 'string', 'max:255'],
            'lieu'            => ['nullable', 'string', 'max:255'],
            'date_debut'      => $creation
                ? ['required', 'date', 'after_or_equal:today']
                : ['required', 'date'],
            'date_fin'        => ['nullable', 'date', 'after_or_equal:date_debut'],
            'services'        => ['required', 'array', 'min:1'],
            'services.*'      => [Rule::in(array_keys(DemandesCouverture::SERVICES))],
            'message'         => ['nullable', 'string', 'max:2000'],
            'statut'          => ['required', Rule::in(array_keys(self::STATUTS))],
            'montant'         => ['nullable', 'integer', 'min:0'],
            'note_interne'    => ['nullable', 'string', 'max:2000'],
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
            'integer'                   => 'Le champ :attribute doit être un nombre entier.',
            'min'                       => 'Le champ :attribute doit être supérieur ou égal à :min.',
            'in'                        => 'La valeur choisie pour :attribute est invalide.',
            'services.required'         => 'Choisissez au moins un service.',
            'services.min'              => 'Choisissez au moins un service.',
            'date_debut.after_or_equal' => 'La date de début ne peut pas être dans le passé.',
            'date_fin.after_or_equal'   => 'La date de fin doit être égale ou postérieure à la date de début.',
        ];
    }

    private function attributes(): array
    {
        return [
            'nom'             => 'nom',
            'telephone'       => 'téléphone',
            'titre_evenement' => 'titre de l’événement',
            'date_debut'      => 'date de début',
            'date_fin'        => 'date de fin',
            'montant'         => 'devis',
            'note_interne'    => 'note interne',
        ];
    }

    public function index(Request $request)
    {
        $filtre = $request->query('statut');
        $filtre = array_key_exists((string) $filtre, self::STATUTS) ? $filtre : null;

        return view('admin.couverture-mediatique.couverture-mediatique', [
            'demandes'     => DemandesCouverture::when($filtre, fn($q) => $q->where('statut', $filtre))
                ->orderByDesc('date_debut')
                ->orderByDesc('id')
                ->get(),
            'counts'       => DemandesCouverture::selectRaw('statut, count(*) as total')->groupBy('statut')->pluck('total', 'statut'),
            'statuts'      => self::STATUTS,
            'servicesList' => DemandesCouverture::SERVICES,
            'filtre'       => $filtre,
        ]);
    }

    /** Saisie manuelle d'une demande (téléphone, rencontre…). */
    public function store(Request $request)
    {
        $data = $request->validate($this->rules(), $this->messages(), $this->attributes());

        try {
            $demande = DemandesCouverture::create($data); // la référence est générée par le modèle
        } catch (\Throwable $e) {
            report($e);

            return back()->withInput()
                ->withErrors(['global' => 'Une erreur est survenue, veuillez réessayer.']);
        }

        return redirect()->route('admin.demandes-couverture.index')
            ->with('success', "La demande {$demande->fresh()->reference} de « {$demande->nom} » a été créée.");
    }

    /**
     * Modification des informations de la demande.
     * Le statut, le devis et la note interne ne sont PAS modifiés ici (voir updateStatut).
     */
    public function update(Request $request, DemandesCouverture $demande)
    {
        $rules = $this->rules(false);
        unset($rules['statut'], $rules['montant'], $rules['note_interne']);

        $data = $request->validate($rules, $this->messages(), $this->attributes());

        try {
            $demande->update($data);
        } catch (\Throwable $e) {
            report($e);

            return back()->withInput()
                ->withErrors(['global' => 'Une erreur est survenue, veuillez réessayer.'], 'edit');
        }

        return redirect()->route('admin.demandes-couverture.index')
            ->with('success', "La demande {$demande->reference} de « {$demande->nom} » a été mise à jour.");
    }

    /** Changement de statut (modal) : statut + devis + note interne. */
    public function updateStatut(Request $request, DemandesCouverture $demande)
    {
        $data = $request->validate([
            'statut'       => ['required', Rule::in(array_keys(self::STATUTS))],
            'montant'      => ['nullable', 'integer', 'min:0'],
            'note_interne' => ['nullable', 'string', 'max:2000'],
        ], $this->messages(), $this->attributes());

        try {
            $demande->update([
                'statut'       => $data['statut'],
                'montant'      => $data['montant'] ?? null,
                'note_interne' => $data['note_interne'] ?? null,
            ]);
        } catch (\Throwable $e) {
            report($e);

            return back()->withInput()
                ->withErrors(['global' => 'Une erreur est survenue, veuillez réessayer.'], 'edit');
        }

        return back()->with('success', "La demande {$demande->reference} est maintenant « " . self::STATUTS[$data['statut']] . ' ».');
    }

    public function destroy(DemandesCouverture $demande)
    {
        $dateRef = $demande->date_fin ?? $demande->date_debut;

        if ($demande->statut === DemandesCouverture::CONFIRMEE && $dateRef->gte(today())) {
            return redirect()->route('admin.demandes-couverture.index')
                ->with('error', 'Impossible de supprimer une demande confirmée à venir. Annulez-la d’abord.');
        }

        try {
            $demande->delete();
        } catch (\Throwable $e) {
            report($e);

            return redirect()->route('admin.demandes-couverture.index')
                ->with('error', 'Impossible de supprimer cette demande.');
        }

        return redirect()->route('admin.demandes-couverture.index')
            ->with('success', 'La demande a été supprimée.');
    }
}
