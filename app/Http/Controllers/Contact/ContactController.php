<?php

namespace App\Http\Controllers\Contact;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ContactController extends Controller
{
    private function rules(bool $creation = true): array
    {
        return [
            'nom'       => ['required', 'string', 'max:255'],
            'telephone' => ['required', 'string', 'max:30'],
            'email'     => ['nullable', 'email', 'max:255'],
            'sujet'     => ['required', Rule::in(array_keys(ContactMessage::SUJETS))],
            'message'   => [$creation ? 'required' : 'nullable', 'string', 'min:10', 'max:3000'],
            'est_lu'    => ['nullable', 'boolean'],
            'ip_address' => ['nullable', 'string', 'max:45'],
        ];
    }

    private function messages(): array
    {
        return [
            'required' => 'Le champ :attribute est obligatoire.',
            'string'   => 'Le champ :attribute est invalide.',
            'max'      => 'Le champ :attribute est trop long (:max caractères maximum).',
            'min'      => 'Le champ :attribute doit contenir au moins :min caractères.',
            'email'    => 'L’adresse e-mail est invalide.',
            'boolean'  => 'Le champ :attribute doit être vrai ou faux.',
            'in'       => 'La valeur choisie pour :attribute est invalide.',
        ];
    }

    private function attributes(): array
    {
        return [
            'nom'       => 'nom',
            'telephone' => 'téléphone',
            'email'     => 'e-mail',
            'sujet'     => 'sujet',
            'message'   => 'message',
            'est_lu'    => 'statut lu',
        ];
    }

    public function index(Request $request)
    {
        $luFiltre = $request->query('lu');
        $luFiltre = in_array((string) $luFiltre, ['0', '1'], true) ? (bool) (int) $luFiltre : null;

        $sujetFiltre = $request->query('sujet');
        $sujetFiltre = array_key_exists((string) $sujetFiltre, ContactMessage::SUJETS) ? $sujetFiltre : null;

        $messages = ContactMessage::query()
            ->when($luFiltre !== null, fn ($query) => $query->where('est_lu', $luFiltre))
            ->when($sujetFiltre, fn ($query) => $query->where('sujet', $sujetFiltre))
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->get();

        $counts = [
            'non_lus' => ContactMessage::where('est_lu', false)->count(),
            'lus'     => ContactMessage::where('est_lu', true)->count(),
        ];

        foreach (ContactMessage::SUJETS as $key => $label) {
            $counts[$key] = ContactMessage::where('sujet', $key)->count();
        }

        $filtre = null;
        if ($luFiltre !== null) {
            $filtre = $luFiltre ? 'lus' : 'non_lus';
        } elseif ($sujetFiltre) {
            $filtre = $sujetFiltre;
        }

        return view('admin.contact.contact', [
            'messages' => $messages,
            'counts'   => collect($counts)->map(fn ($value) => (int) $value)->all(),
            'filtre'   => $filtre,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate($this->rules(), $this->messages(), $this->attributes());

        try {
            $message = ContactMessage::create($data);
        } catch (\Throwable $e) {
            report($e);

            return back()->withInput()
                ->withErrors(['global' => 'Une erreur est survenue, veuillez réessayer.']);
        }

        return redirect()->route('admin.contacts.index')
            ->with('success', 'Le message de « ' . $message->nom . ' » a été ajouté.');
    }

    public function update(Request $request, ContactMessage $contact)
    {
        $data = $request->validate($this->rules(false), $this->messages(), $this->attributes());

        try {
            $contact->update($data);
        } catch (\Throwable $e) {
            report($e);

            return back()->withInput()
                ->withErrors(['global' => 'Une erreur est survenue, veuillez réessayer.'], 'edit');
        }

        return redirect()->route('admin.contacts.index')
            ->with('success', 'Le message de « ' . $contact->nom . ' » a été mis à jour.');
    }

    public function updateStatut(Request $request, ContactMessage $contact)
    {
        $data = $request->validate([
            'est_lu' => ['required', 'boolean'],
        ], $this->messages(), $this->attributes());

        try {
            $contact->update([
                'est_lu' => $data['est_lu'],
            ]);
        } catch (\Throwable $e) {
            report($e);

            return back()->withInput()
                ->withErrors(['global' => 'Une erreur est survenue, veuillez réessayer.'], 'edit');
        }

        return back()->with('success', 'Le statut du message de « ' . $contact->nom . ' » a été mis à jour.');
    }

    public function destroy(ContactMessage $contact)
    {
        try {
            $contact->delete();
        } catch (\Throwable $e) {
            report($e);

            return redirect()->route('admin.contacts.index')
                ->with('error', 'Impossible de supprimer ce message.');
        }

        return redirect()->route('admin.contacts.index')
            ->with('success', 'Le message a été supprimé.');
    }
}
