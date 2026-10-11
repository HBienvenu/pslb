<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Mail\ContactConfirmationMail;
use App\Mail\LivraisonConfirmationMail;
use App\Mail\MediaDemandConfirmationMail;
use App\Mail\ReservationConfirmationMail;
use App\Models\CategorieVehicule;
use App\Models\ContactMessage;
use App\Models\DemandesCouverture;
use App\Models\Livraison;
use App\Models\Reservation;
use App\Models\Vehicule;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;

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

    /**
     * Enregistre la demande de réservation envoyée depuis le formulaire public.
     * La demande est créée avec le statut « en_attente » : l'admin la confirme ou la refuse ensuite.
     */
    public function reserverVehicule(Request $request)
    {
        // Honeypot : un humain ne remplit pas ce champ caché. On répond « succès » pour ne pas renseigner le robot.
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
                ->confirmeesSur($validated['date_debut'], $validated['date_fin'])
                ->exists();

            if ($occupe) {
                return back()->withInput()
                    ->withErrors(['date_debut' => 'Ce véhicule est déjà réservé sur cette période. Choisissez d’autres dates ou un autre véhicule.']);
            }
        }

        try {
            // La référence (LOC-2026-0042) est générée automatiquement par le modèle Reservation
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
                'statut'         => Reservation::EN_ATTENTE,
                'ip_address'     => $request->ip(),
            ]);

            if (!empty($validated['email'])) {
                try {
                    Mail::to($validated['email'])->send(new ReservationConfirmationMail(
                        nom: $validated['nom'],
                        telephone: $validated['telephone'],
                        email: $validated['email'],
                        vehicule: $vehicule?->nom ?? null,
                        dateDebut: $validated['date_debut'],
                        dateFin: $validated['date_fin'],
                        lieuPrise: $validated['lieu_prise'] ?? null,
                        message: $validated['message'] ?? null,
                    ));
                } catch (\Throwable $e) {
                    report($e);
                }
            }
        } catch (\Throwable $e) {
            report($e);

            return back()->withInput()
                ->withErrors(['global' => 'Une erreur est survenue, veuillez réessayer.']);
        }

        // Nouveau calcul anti-robot au prochain affichage
        session()->forget(['loc_captcha_a', 'loc_captcha_b']);

        return back()->with('success', 'Votre demande a bien été envoyée. Nous vous recontactons très vite pour la confirmer.');
    }

    public function livraison()
    {
        return view('site.livraison.livraison', [
            'typesColis' => [
                'document' => 'Document / courrier',
                'petit' => 'Petit colis',
                'moyen' => 'Colis moyen',
                'gros' => 'Gros colis / volumineux',
                'fragile' => 'Colis fragile',
            ],
        ]);
    }

    public function storeLivraison(Request $request)
    {
        if ($request->filled('website')) {
            return back()->with('success', 'Votre demande a bien été enregistrée.');
        }

        $validated = $request->validate([
            'nom' => ['required', 'string', 'max:255'],
            'telephone' => ['required', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:255'],
            'ville_depart' => ['required', 'string', 'max:255'],
            'adresse_depart' => ['required', 'string', 'max:500'],
            'ville_arrivee' => ['required', 'string', 'max:255'],
            'adresse_livraison' => ['required', 'string', 'max:500'],
            'type_colis' => ['required', 'string', 'max:255'],
            'poids_kg' => ['nullable', 'numeric', 'min:0'],
            'date_livraison' => ['required', 'date', 'after_or_equal:today'],
            'heure_livraison' => ['nullable', 'string', 'max:20'],
            'description' => ['nullable', 'string', 'max:2000'],
            'image_colis' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'captcha' => ['required', 'numeric'],
        ], [
            'required' => 'Le champ :attribute est obligatoire.',
            'string' => 'Le champ :attribute est invalide.',
            'email' => 'L’adresse e-mail est invalide.',
            'date' => 'Le champ :attribute doit être une date valide.',
            'numeric' => 'La réponse de vérification doit être un nombre.',
            'image' => 'Le fichier doit être une image valide.',
            'mimes' => 'L’image doit être au format JPG, JPEG, PNG ou WEBP.',
            'max' => 'Le fichier est trop volumineux (max :max KB).',
            'date_livraison.after_or_equal' => 'La date de livraison ne peut pas être dans le passé.',
        ], [
            'nom' => 'nom complet',
            'telephone' => 'téléphone',
            'ville_depart' => 'ville de départ',
            'adresse_depart' => 'adresse de départ',
            'ville_arrivee' => 'ville d’arrivée',
            'adresse_livraison' => 'adresse de livraison',
            'type_colis' => 'type de colis',
            'poids_kg' => 'poids',
            'date_livraison' => 'date de livraison',
            'image_colis' => 'image du colis',
        ]);

        $attendu = (int) session('livraison_captcha_a') + (int) session('livraison_captcha_b');

        if (!session()->has('livraison_captcha_a') || (int) $validated['captcha'] !== $attendu) {
            return back()->withInput()
                ->withErrors(['captcha' => 'La réponse à la vérification anti-robot est incorrecte.']);
        }

        try {
            $path = $request->file('image_colis')->store('livraisons', 'public');

            $livraison = Livraison::create([
                'nom' => $validated['nom'],
                'telephone' => $validated['telephone'],
                'email' => $validated['email'] ?? null,
                'ville_depart' => $validated['ville_depart'],
                'adresse_depart' => $validated['adresse_depart'],
                'ville_arrivee' => $validated['ville_arrivee'],
                'adresse_livraison' => $validated['adresse_livraison'],
                'type_colis' => $validated['type_colis'],
                'poids_kg' => $validated['poids_kg'] ?? null,
                'description' => $validated['description'] ?? null,
                'date_livraison' => $validated['date_livraison'],
                'heure_livraison' => $validated['heure_livraison'] ?? null,
                'image_colis' => $path,
                'statut' => Livraison::EN_ATTENTE,
                'ip_address' => $request->ip(),
            ]);

            if (!empty($validated['email'])) {
                try {
                    Mail::to($validated['email'])->send(new LivraisonConfirmationMail(
                        nom: $validated['nom'],
                        telephone: $validated['telephone'],
                        email: $validated['email'],
                        villeDepart: $validated['ville_depart'],
                        adresseDepart: $validated['adresse_depart'],
                        villeArrivee: $validated['ville_arrivee'],
                        adresseLivraison: $validated['adresse_livraison'],
                        typeColis: $validated['type_colis'],
                        poidsKg: $validated['poids_kg'] ? (string) $validated['poids_kg'] : null,
                        dateLivraison: $validated['date_livraison'],
                        heureLivraison: $validated['heure_livraison'] ?? null,
                        description: $validated['description'] ?? null,
                    ));
                } catch (\Throwable $e) {
                    report($e);
                }
            }
        } catch (\Throwable $e) {
            report($e);

            return back()->withInput()
                ->withErrors(['global' => 'Une erreur est survenue, veuillez réessayer.']);
        }

        session()->forget(['livraison_captcha_a', 'livraison_captcha_b']);

        return back()->with('success', 'Votre demande de livraison a bien été enregistrée. Nous vous recontactons très vite pour la confirmer.');
    }

    // Page media front
    public function media()
    {
        return view('site.media.media', [
            'services' => DemandesCouverture::SERVICES,
        ]);
    }

    public function demandeServiceMedia(Request $request)
    {
        // Honeypot : un humain ne remplit pas ce champ caché. On répond « succès » pour ne pas renseigner le robot.
        if ($request->filled('website')) {
            return back()->with('success', 'Votre demande a bien été envoyée.');
        }

        $validated = $request->validate([
            'nom'             => ['required', 'string', 'max:255'],
            'telephone'       => ['required', 'string', 'max:30'],
            'email'           => ['nullable', 'email', 'max:255'],
            'organisation'    => ['nullable', 'string', 'max:255'],
            'titre_evenement' => ['required', 'string', 'max:255'],
            'lieu'            => ['nullable', 'string', 'max:255'],
            'date_debut'      => ['required', 'date', 'after_or_equal:today'],
            'date_fin'        => ['nullable', 'date', 'after_or_equal:date_debut'],
            'services'        => ['required', 'array', 'min:1'],
            'services.*'      => [Rule::in(array_keys(DemandesCouverture::SERVICES))],
            'message'         => ['nullable', 'string', 'max:2000'],
            'captcha'         => ['required', 'numeric'],
        ], [
            'required'                  => 'Le champ :attribute est obligatoire.',
            'max'                       => 'Le champ :attribute est trop long (:max caractères maximum).',
            'email'                     => 'L’adresse e-mail est invalide.',
            'date'                      => 'Le champ :attribute doit être une date valide.',
            'numeric'                   => 'La réponse de vérification doit être un nombre.',
            'in'                        => 'Le service choisi est invalide.',
            'services.required'         => 'Choisissez au moins un service.',
            'services.min'              => 'Choisissez au moins un service.',
            'date_debut.after_or_equal' => 'La date de début ne peut pas être dans le passé.',
            'date_fin.after_or_equal'   => 'La date de fin doit être égale ou postérieure à la date de début.',
        ], [
            'nom'             => 'nom complet',
            'telephone'       => 'téléphone',
            'titre_evenement' => 'nom de l’événement',
            'date_debut'      => 'date de début',
            'date_fin'        => 'date de fin',
        ]);

        // Vérification anti-robot (valeurs générées dans la vue et stockées en session)
        $attendu = (int) session('media_captcha_a') + (int) session('media_captcha_b');

        if (!session()->has('media_captcha_a') || (int) $validated['captcha'] !== $attendu) {
            return back()->withInput()
                ->withErrors(['captcha' => 'La réponse à la vérification anti-robot est incorrecte.']);
        }

        try {
            // La référence (MED-2026-0042) est générée automatiquement par le modèle
            $demande = DemandesCouverture::create([
                'nom'             => $validated['nom'],
                'telephone'       => $validated['telephone'],
                'email'           => $validated['email'] ?? null,
                'organisation'    => $validated['organisation'] ?? null,
                'titre_evenement' => $validated['titre_evenement'],
                'lieu'            => $validated['lieu'] ?? null,
                'date_debut'      => $validated['date_debut'],
                'date_fin'        => $validated['date_fin'] ?? null,
                'services'        => array_values($validated['services']),
                'message'         => $validated['message'] ?? null,
                'statut'          => DemandesCouverture::EN_ATTENTE,
                'ip_address'      => $request->ip(),
            ]);

            if (!empty($validated['email'])) {
                try {
                    Mail::to($validated['email'])->send(new MediaDemandConfirmationMail(
                        nom: $validated['nom'],
                        telephone: $validated['telephone'],
                        email: $validated['email'],
                        organisation: $validated['organisation'] ?? null,
                        titreEvenement: $validated['titre_evenement'],
                        lieu: $validated['lieu'] ?? null,
                        dateDebut: $validated['date_debut'],
                        dateFin: $validated['date_fin'] ?? null,
                        services: array_values($validated['services']),
                        message: $validated['message'] ?? null,
                    ));
                } catch (\Throwable $e) {
                    report($e);
                }
            }
        } catch (\Throwable $e) {
            report($e);

            return back()->withInput()
                ->withErrors(['global' => 'Une erreur est survenue, veuillez réessayer.']);
        }

        // Nouveau calcul anti-robot au prochain affichage
        session()->forget(['media_captcha_a', 'media_captcha_b']);

        return back()->with('success', 'Votre demande a bien été envoyée. Nous vous recontactons très vite avec une proposition.');
    }

    public function aPropos()
    {
        return view('site.apropos.apropos');
    }

    public function contact()
    {
        return view('site.contact.contact');
    }

    public function storeContact(Request $request)
    {
        // Honeypot : un bot a rempli le champ caché → on fait semblant que tout va bien
        if ($request->filled('website')) {
            return redirect()->route('contact')
                ->with('success', 'Merci, votre message a bien été envoyé.');
        }

        $a = (int) session('contact_captcha_a');
        $b = (int) session('contact_captcha_b');

        $validated = $request->validate([
            'nom'        => ['required', 'string', 'max:255'],
            'telephone'  => ['required', 'string', 'max:30'],
            'email'      => ['nullable', 'email', 'max:255'],
            'sujet_type' => ['required', Rule::in(array_keys(ContactMessage::SUJETS))],
            'message'    => ['required', 'string', 'min:10', 'max:3000'],
            'captcha'    => [
                'required',
                'integer',
                fn($attribute, $value, $fail) => (int) $value === $a + $b
                    ?: $fail('La vérification anti-robot est incorrecte.'),
            ],
        ], [
            'required'   => 'Le champ :attribute est obligatoire.',
            'string'     => 'Le champ :attribute est invalide.',
            'max'        => 'Le champ :attribute est trop long (:max caractères maximum).',
            'min'        => 'Le champ :attribute est trop court (:min caractères minimum).',
            'email'      => 'Veuillez saisir une adresse email valide.',
            'integer'    => 'Le champ :attribute doit être un nombre.',
            'in'         => 'La valeur choisie pour :attribute est invalide.',
        ], [
            'nom'        => 'nom complet',
            'telephone'  => 'téléphone',
            'sujet_type' => 'sujet',
            'captcha'    => 'vérification anti-robot',
        ]);

        try {
            $contact = ContactMessage::create([
                'nom'        => $validated['nom'],
                'telephone'  => $validated['telephone'],
                'email'      => $validated['email'] ?? null,
                'sujet'      => $validated['sujet_type'],
                'message'    => $validated['message'],
                'ip_address' => $request->ip(),
            ]);

            if (!empty($validated['email'])) {
                try {
                    Mail::to($validated['email'])->send(new ContactConfirmationMail(
                        nom: $validated['nom'],
                        telephone: $validated['telephone'],
                        email: $validated['email'],
                        sujet: ContactMessage::SUJETS[$validated['sujet_type']] ?? $validated['sujet_type'],
                        message: $validated['message'],
                    ));
                } catch (\Throwable $e) {
                    report($e);
                }
            }
        } catch (\Throwable $e) {
            report($e);

            return back()->withInput()
                ->withErrors(['global' => 'Une erreur est survenue, veuillez réessayer.']);
        }

        // Nouvelle question anti-robot au prochain affichage
        session()->forget(['contact_captcha_a', 'contact_captcha_b']);

        return redirect()->route('contact')
            ->with('success', 'Merci, votre message a bien été envoyé. Nous vous répondons rapidement.');
    }
}
