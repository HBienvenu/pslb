<?php

namespace App\Http\Controllers\Formation;

use App\Http\Controllers\Controller;
use App\Models\DemandeFormation;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class FormationController extends Controller
{
    public const STATUTS = [
        DemandeFormation::EN_ATTENTE => 'En attente',
        DemandeFormation::EN_COURS => 'En cours',
        DemandeFormation::TRAITE => 'Traité',
        DemandeFormation::VALIDE => 'Validé',
        DemandeFormation::REFUSE => 'Refusé',
    ];

    private function rules(bool $creation = true): array
    {
        return [
            'nom' => ['required', 'string', 'max:255'],
            'telephone' => ['required', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:255'],
            'service' => ['required', Rule::in(array_keys(DemandeFormation::SERVICES))],
            'budget' => ['nullable', 'numeric', 'min:0'],
            'date_prevue' => $creation ? ['nullable', 'date', 'after_or_equal:today'] : ['nullable', 'date'],
            'description' => ['nullable', 'string', 'max:2000'],
            'statut' => ['required', Rule::in(array_keys(self::STATUTS))],
            'note_interne' => ['nullable', 'string', 'max:2000'],
        ];
    }

    private function messages(): array
    {
        return [
            'required' => 'Le champ :attribute est obligatoire.',
            'string' => 'Le champ :attribute est invalide.',
            'email' => 'L’adresse e-mail est invalide.',
            'numeric' => 'Le champ :attribute doit être un nombre.',
            'date' => 'Le champ :attribute doit être une date valide.',
            'min' => 'Le champ :attribute doit être supérieur ou égal à :min.',
            'in' => 'La valeur choisie pour :attribute est invalide.',
            'date_prevue.after_or_equal' => 'La date prévue ne peut pas être dans le passé.',
            'max' => 'Le champ :attribute est trop long (:max caractères maximum).',
        ];
    }

    private function attributes(): array
    {
        return [
            'nom' => 'nom',
            'telephone' => 'téléphone',
            'service' => 'service demandé',
            'budget' => 'budget estimé',
            'date_prevue' => 'date prévue',
            'note_interne' => 'note interne',
        ];
    }

    public function index(Request $request)
    {
        $filtre = $request->query('statut');
        $filtre = array_key_exists((string) $filtre, self::STATUTS) ? $filtre : null;

        return view('admin.formation.formation', [
            'demandes' => DemandeFormation::when($filtre, fn ($q) => $q->where('statut', $filtre))
                ->orderByDesc('created_at')
                ->get(),
            'counts' => DemandeFormation::selectRaw('statut, count(*) as total')->groupBy('statut')->pluck('total', 'statut'),
            'statuts' => self::STATUTS,
            'filtre' => $filtre,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate($this->rules(), $this->messages(), $this->attributes());

        try {
            $demande = DemandeFormation::create($data);
        } catch (\Throwable $e) {
            report($e);

            return back()->withInput()->withErrors(['global' => 'Une erreur est survenue, veuillez réessayer.']);
        }

        return redirect()->route('admin.demandes-formation.index')
            ->with('success', "La demande {$demande->fresh()->reference} de « {$demande->nom} » a été créée.");
    }

    public function update(Request $request, DemandeFormation $demande)
    {
        $rules = $this->rules(false);
        unset($rules['statut'], $rules['note_interne']);

        $data = $request->validate($rules, $this->messages(), $this->attributes());

        try {
            $demande->update($data);
        } catch (\Throwable $e) {
            report($e);

            return back()->withInput()->withErrors(['global' => 'Une erreur est survenue, veuillez réessayer.'], 'edit');
        }

        return redirect()->route('admin.demandes-formation.index')
            ->with('success', "La demande {$demande->reference} de « {$demande->nom} » a été mise à jour.");
    }

    public function updateStatut(Request $request, DemandeFormation $demande)
    {
        $data = $request->validate([
            'statut' => ['required', Rule::in(array_keys(self::STATUTS))],
            'note_interne' => ['nullable', 'string', 'max:2000'],
            'budget' => ['nullable', 'numeric', 'min:0'],
        ], $this->messages(), $this->attributes());

        try {
            $demande->update([
                'statut' => $data['statut'],
                'note_interne' => $data['note_interne'] ?? null,
                'budget' => $data['budget'] ?? null,
            ]);
        } catch (\Throwable $e) {
            report($e);

            return back()->withInput()->withErrors(['global' => 'Une erreur est survenue, veuillez réessayer.'], 'edit');
        }

        return back()->with('success', "La demande {$demande->reference} est maintenant « " . self::STATUTS[$data['statut']] . ' ».');
    }

    public function destroy(DemandeFormation $demande)
    {
        try {
            $demande->delete();
        } catch (\Throwable $e) {
            report($e);

            return redirect()->route('admin.demandes-formation.index')
                ->with('error', 'Impossible de supprimer cette demande.');
        }

        return redirect()->route('admin.demandes-formation.index')
            ->with('success', 'La demande a été supprimée.');
    }
}
