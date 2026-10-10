@extends('layouts.site.app', ['title' => 'Location de voiture'])

@section('content')
    @php
        $heroImg = 'https://images.unsplash.com/photo-1550355291-bbee04a92027?auto=format&fit=crop&w=1600&q=80';
        $input = 'w-full px-4 py-3 rounded-xl border border-pslb-border bg-pslb-surface text-pslb-ink placeholder-gray-400 text-sm focus:outline-none focus:border-pslb-blue-800 focus:ring-2 focus:ring-pslb-blue-800/20 transition-all';
        $field = fn($name) => $input . ($errors->has($name) ? ' !border-red-400' : '');
        $check = '<path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75"/>';
    @endphp

    {{-- ==================== HERO ==================== --}}
    <section class="relative pt-32 pb-24 md:pt-44 md:pb-32 overflow-hidden bg-cover bg-center"
        style="background-image:url('{{ $heroImg }}')">
        <div class="absolute inset-0 bg-gradient-to-br from-pslb-blue-900/90 to-pslb-blue-800/75"></div>
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="max-w-4xl mx-auto">
                <div data-aos="fade-down"
                    class="inline-flex items-center gap-2 bg-white/10 backdrop-blur-sm rounded-full px-4 py-1.5 mb-6">
                    <span class="w-2 h-2 bg-pslb-cyan-500 rounded-full animate-pulse"></span>
                    <span class="text-white text-sm font-medium uppercase tracking-widest">Location de voiture</span>
                </div>
                <h1 data-aos="fade-up" data-aos-delay="100"
                    class="text-4xl md:text-5xl lg:text-6xl font-extrabold text-white leading-tight">
                    Le bon véhicule,<br><span class="text-pslb-cyan-500">pour chaque trajet</span>
                </h1>
                <p data-aos="fade-up" data-aos-delay="200" class="text-lg md:text-xl text-gray-200 mt-6 max-w-2xl">
                    Déplacements personnels, professionnels ou événementiels · Avec ou sans chauffeur.<br>
                    Choisissez votre véhicule et réservez en quelques minutes.
                </p>
                <div data-aos="fade-up" data-aos-delay="300" class="flex flex-col sm:flex-row gap-4 mt-10">
                    <a href="#flotte"
                        class="bg-pslb-cyan-500 hover:bg-white hover:text-pslb-blue-800 text-white px-8 py-3 rounded-full text-lg font-semibold shadow-xl inline-flex items-center justify-center gap-2 group transition-colors">
                        Voir nos véhicules
                        <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 7l5 5m0 0l-5 5m5-5H6" />
                        </svg>
                    </a>
                    <a href="#reservation"
                        class="border border-white/40 hover:bg-white/10 text-white px-8 py-3 rounded-full text-lg font-semibold inline-flex items-center justify-center transition-colors">
                        Réserver maintenant
                    </a>
                </div>
            </div>
        </div>
        <div class="absolute bottom-0 left-0 w-full">
            <svg viewBox="0 0 1200 120" preserveAspectRatio="none" class="relative block w-full h-12 md:h-16">
                <path
                    d="M0,0V5.63C149.93,59,314.09,71.32,475.83,42.57c77.44-13.74,149.51-38.5,229.58-45.86C870.13-9.08,1049.16,21.06,1200,39.66V0Z"
                    fill="white" />
            </svg>
        </div>
    </section>

    {{-- ==================== CHIFFRES CLÉS ==================== --}}
    <section class="py-14 bg-white border-b border-pslb-border overflow-hidden">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            @php
                $stats = [
                    ['value' => $vehicules->count(), 'label' => 'Véhicules disponibles'],
                    ['value' => $categories->count(), 'label' => 'Catégories de véhicules'],
                    ['value' => '7j/7', 'label' => 'Prise de contact'],
                    ['value' => '2', 'label' => 'Options : avec ou sans chauffeur'],
                ];
            @endphp
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
                @foreach ($stats as $stat)
                    <div data-aos="zoom-in-up">
                        <div class="stat-number">{{ $stat['value'] }}</div>
                        <p class="text-pslb-muted font-medium mt-1">{{ $stat['label'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ==================== USAGES ==================== --}}
    <section class="py-20 bg-white overflow-hidden">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16" data-aos="fade-up">
                <span class="text-pslb-cyan-500 font-semibold tracking-wider uppercase text-sm">Pour chaque besoin</span>
                <h2 class="text-3xl md:text-4xl font-bold text-pslb-blue-800 mt-2">
                    Une location <span class="text-pslb-cyan-500">adaptée à votre usage</span>
                </h2>
                <div class="w-24 h-1 bg-pslb-cyan-500 mx-auto mt-5 rounded-full"></div>
            </div>
            @php
                $usages = [
                    ['title' => 'Déplacements personnels', 'text' => 'Courses, famille, voyages, rendez-vous : roulez à votre rythme.', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>'],
                    ['title' => 'Déplacements professionnels', 'text' => 'Missions, visites clients, accueil de partenaires et d\'invités.', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>'],
                    ['title' => 'Événements', 'text' => 'Mariages, cérémonies, séminaires, lancements : arrivez avec style.', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/>'],
                    ['title' => 'Avec ou sans chauffeur', 'text' => 'Conduisez vous-même ou laissez-nous conduire : à préciser à la réservation.', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z"/>'],
                ];
            @endphp
            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach ($usages as $i => $u)
                    <div class="bg-pslb-surface rounded-2xl p-7 shadow-sm card-hover border-l-4 border-pslb-cyan-500"
                        data-aos="fade-up" data-aos-delay="{{ $i * 100 }}">
                        <div class="w-14 h-14 bg-pslb-blue-800/10 rounded-xl flex items-center justify-center mb-5">
                            <svg class="w-7 h-7 text-pslb-cyan-500" fill="none" stroke="currentColor" stroke-width="1.8"
                                viewBox="0 0 24 24">{!! $u['icon'] !!}</svg>
                        </div>
                        <h3 class="text-lg font-bold text-pslb-blue-800">{{ $u['title'] }}</h3>
                        <p class="text-pslb-muted text-sm mt-2 leading-relaxed">{{ $u['text'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ==================== FLOTTE ==================== --}}
    <section id="flotte" class="py-20 bg-pslb-surface overflow-hidden">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-10" data-aos="fade-up">
                <span class="text-pslb-cyan-500 font-semibold uppercase tracking-wide text-sm">Notre flotte</span>
                <h2 class="text-3xl md:text-4xl font-bold text-pslb-blue-800 mt-1">
                    Nos véhicules <span class="text-pslb-cyan-500">disponibles</span>
                </h2>
                <div class="w-16 h-1 bg-pslb-cyan-500 rounded-full mt-3"></div>
            </div>

            @if ($vehicules->isEmpty())
                <div class="text-center py-16 text-gray-400">
                    <p class="font-medium">Aucun véhicule disponible pour le moment.</p>
                    <p class="text-sm mt-1">Contactez-nous, nous vous proposerons une solution.</p>
                </div>
            @else
                {{-- Filtres par catégorie --}}
                <div class="flex flex-wrap gap-2 mb-8" data-aos="fade-up">
                    <button type="button" data-filter="all"
                        class="px-5 py-2 rounded-full text-sm font-semibold border border-pslb-border bg-pslb-blue-800 text-white transition-colors">Tous</button>
                    @foreach ($categories as $cat)
                        <button type="button" data-filter="{{ $cat->id }}"
                            class="px-5 py-2 rounded-full text-sm font-semibold border border-pslb-border bg-white text-pslb-blue-800 hover:border-pslb-cyan-500 transition-colors">{{ $cat->nom }}</button>
                    @endforeach
                </div>

                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($vehicules as $i => $v)
                        <article data-category="{{ $v->categorie_id }}"
                            class="vehicule-card group bg-white rounded-2xl border border-pslb-border hover:border-pslb-cyan-500/40 shadow-sm hover:shadow-lg transition-all duration-300 overflow-hidden flex flex-col"
                            data-aos="fade-up" data-aos-delay="{{ ($i % 3) * 80 }}">
                            <div class="h-48 bg-pslb-blue-800/10 overflow-hidden">
                                @if ($v->image)
                                    <img src="{{ asset('storage/' . $v->image) }}" alt="{{ $v->nom }}" loading="lazy"
                                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                @endif
                            </div>
                            <div class="p-5 flex flex-col flex-1">
                                @if ($v->categorie)
                                    <p class="text-[11px] font-semibold uppercase tracking-widest text-pslb-cyan-500 mb-1">
                                        {{ $v->categorie->nom }}</p>
                                @endif
                                <h3 class="text-lg font-bold text-pslb-blue-800">{{ $v->nom }}</h3>
                                <div class="flex flex-wrap gap-x-4 gap-y-1 text-xs text-pslb-muted mt-3">
                                    @if ($v->nombre_places)<span>{{ $v->nombre_places }} places</span>@endif
                                    @if ($v->boite_vitesse)<span>{{ $v->boite_vitesse }}</span>@endif
                                    @if ($v->carburant)<span>{{ $v->carburant }}</span>@endif
                                    @if ($v->climatisation)<span>Climatisée</span>@endif
                                </div>
                                <div class="mt-auto pt-4 flex items-center justify-between border-t border-pslb-border mt-4">
                                    <p class="font-bold text-pslb-blue-800">
                                        @if ($v->prix_jour)
                                            {{ formatMontantFCFA($v->prix_jour) }} <span
                                                class="text-xs font-medium text-pslb-muted">/ jour</span>
                                        @else
                                            <span class="text-sm">Sur devis</span>
                                        @endif
                                    </p>
                                    <a href="#reservation" data-reserve="{{ $v->id }}"
                                        class="text-xs font-semibold text-white bg-pslb-blue-800 hover:bg-pslb-cyan-500 px-4 py-2 rounded-lg transition-colors">Réserver</a>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
                <p id="no-vehicule" class="hidden text-center py-12 text-pslb-muted">Aucun véhicule dans cette catégorie.</p>
            @endif
        </div>
    </section>

    {{-- ==================== CONDITIONS + PROCESSUS ==================== --}}
    <section class="py-20 bg-white overflow-hidden">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-14" data-aos="fade-up">
                <span class="text-pslb-cyan-500 font-semibold tracking-wider uppercase text-sm">Comment réserver</span>
                <h2 class="text-3xl md:text-4xl font-bold text-pslb-blue-800 mt-2">
                    Un processus <span class="text-pslb-cyan-500">simple en 4 étapes</span>
                </h2>
                <div class="w-24 h-1 bg-pslb-cyan-500 mx-auto mt-5 rounded-full"></div>
            </div>
            @php
                $steps = [
                    ['Choisissez', 'Parcourez la flotte et sélectionnez le véhicule qui vous convient.', 'bg-pslb-cyan-500'],
                    ['Réservez', 'Remplissez le formulaire avec vos dates et vos préférences.', 'bg-pslb-blue-800'],
                    ['Confirmez', 'Nous vous contactons pour confirmer la disponibilité et le tarif.', 'bg-pslb-blue-800'],
                    ['Roulez', 'Récupérez le véhicule, ou votre chauffeur vous attend.', 'bg-pslb-cyan-500'],
                ];
            @endphp
            <div class="grid md:grid-cols-4 gap-8 relative">
                <div class="hidden md:block absolute top-10 left-[12.5%] right-[12.5%] border-t-2 border-dashed border-pslb-cyan-500/30 z-0"></div>
                @foreach ($steps as $i => [$title, $desc, $color])
                    <div class="text-center relative z-10" data-aos="fade-up" data-aos-delay="{{ $i * 150 }}">
                        <div class="w-20 h-20 {{ $color }} rounded-full flex items-center justify-center mx-auto mb-6 shadow-lg">
                            <span class="text-white text-2xl font-extrabold">{{ sprintf('%02d', $i + 1) }}</span>
                        </div>
                        <h3 class="text-xl font-bold text-pslb-blue-800 mb-3">{{ $title }}</h3>
                        <p class="text-pslb-muted text-sm leading-relaxed">{{ $desc }}</p>
                    </div>
                @endforeach
            </div>

            {{-- À prévoir --}}
            <div class="mt-16 max-w-3xl mx-auto bg-pslb-surface rounded-2xl p-8 border-l-4 border-pslb-blue-800" data-aos="fade-up">
                <h3 class="text-lg font-bold text-pslb-blue-800 mb-4">À prévoir pour la location</h3>
                <ul class="grid sm:grid-cols-2 gap-3">
                    @foreach (['Une pièce d\'identité valide', 'Un permis de conduire en cours de validité (sans chauffeur)', 'Une caution, précisée à la réservation', 'La restitution du véhicule à la date convenue'] as $item)
                        <li class="flex items-start gap-2.5 text-sm text-pslb-ink">
                            <svg class="w-4 h-4 text-pslb-cyan-500 shrink-0 mt-0.5" fill="none" stroke="currentColor"
                                stroke-width="2.5" viewBox="0 0 24 24">{!! $check !!}</svg>
                            {{ $item }}
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </section>

    {{-- ==================== RÉSERVATION ==================== --}}
    <turbo-frame id="reservation-voiture">
        <section id="reservation" class="py-20 bg-pslb-surface overflow-hidden">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid lg:grid-cols-2 gap-16 items-start">

                    <div data-aos="fade-right">
                        <span class="text-pslb-cyan-500 font-semibold tracking-wider uppercase text-sm">Réservation</span>
                        <h2 class="text-3xl md:text-4xl font-bold text-pslb-blue-800 mt-2">
                            Réservez <span class="text-pslb-cyan-500">votre véhicule</span>
                        </h2>
                        <div class="w-24 h-1 bg-pslb-cyan-500 mt-5 rounded-full mb-8"></div>
                        <p class="text-pslb-muted leading-relaxed mb-8">
                            Indiquez vos dates et vos préférences : nous vous recontactons pour confirmer la disponibilité et le tarif.
                        </p>
                        <div class="space-y-5">
                            @foreach ([['Téléphone', CONTACT_PSLB], ['WhatsApp', CONTACT_PSLB], ['Adresse', LOCALISATION_PSLB]] as [$label, $value])
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 bg-pslb-cyan-500/10 rounded-xl flex items-center justify-center shrink-0 text-pslb-cyan-500 font-bold">→</div>
                                    <div>
                                        <p class="text-xs text-pslb-muted font-medium uppercase tracking-wide">{{ $label }}</p>
                                        <p class="text-pslb-ink font-semibold">{{ $value }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl shadow-md p-6 md:p-10" data-aos="fade-left">
                        @if (session('success'))
                            <div class="flex items-start gap-3 bg-green-50 border border-green-200 text-green-700 rounded-xl px-4 py-3 text-sm mb-6">
                                <svg class="w-5 h-5 shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                </svg>
                                {{ session('success') }}
                            </div>
                        @endif
                        @if ($errors->any())
                            <div class="bg-red-50 border border-red-200 rounded-xl px-4 py-3 text-sm mb-6">
                                <p class="text-red-700 font-semibold mb-1">Veuillez corriger les erreurs :</p>
                                <ul class="list-disc list-inside text-red-600 space-y-0.5">
                                    @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                                </ul>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('location.reservation.store') }}"
                            data-turbo-frame="reservation-voiture" class="space-y-5">
                            @csrf
                            <input type="text" name="website" value="" style="display:none" tabindex="-1" autocomplete="off">

                            <div class="grid sm:grid-cols-2 gap-5">
                                <div>
                                    <label class="block text-sm font-medium text-pslb-ink mb-1.5">Nom complet <span class="text-red-500">*</span></label>
                                    <input type="text" name="nom" value="{{ old('nom') }}" required placeholder="Jean Dupont" class="{{ $field('nom') }}">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-pslb-ink mb-1.5">Téléphone <span class="text-red-500">*</span></label>
                                    <input type="tel" name="telephone" value="{{ old('telephone') }}" required placeholder="+229 XX XX XX XX" class="{{ $field('telephone') }}">
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-pslb-ink mb-1.5">Email</label>
                                <input type="email" name="email" value="{{ old('email') }}" placeholder="votre@email.com" class="{{ $field('email') }}">
                            </div>

                            <div class="grid sm:grid-cols-2 gap-5">
                                <div>
                                    <label class="block text-sm font-medium text-pslb-ink mb-1.5">Véhicule souhaité</label>
                                    <select name="vehicule_id" id="vehicule_id" class="{{ $field('vehicule_id') }}">
                                        <option value="">Peu importe / à conseiller</option>
                                        @foreach ($vehicules as $v)
                                            <option value="{{ $v->id }}" @selected(old('vehicule_id') == $v->id)>{{ $v->nom }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-pslb-ink mb-1.5">Conduite</label>
                                    <select name="avec_chauffeur" class="{{ $field('avec_chauffeur') }}">
                                        <option value="0" @selected(old('avec_chauffeur') === '0')>Sans chauffeur</option>
                                        <option value="1" @selected(old('avec_chauffeur') === '1')>Avec chauffeur</option>
                                    </select>
                                </div>
                            </div>

                            <div class="grid sm:grid-cols-2 gap-5">
                                <div>
                                    <label class="block text-sm font-medium text-pslb-ink mb-1.5">Début <span class="text-red-500">*</span></label>
                                    <input type="date" name="date_debut" value="{{ old('date_debut') }}" min="{{ now()->toDateString() }}" required class="{{ $field('date_debut') }}">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-pslb-ink mb-1.5">Fin <span class="text-red-500">*</span></label>
                                    <input type="date" name="date_fin" value="{{ old('date_fin') }}" min="{{ now()->toDateString() }}" required class="{{ $field('date_fin') }}">
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-pslb-ink mb-1.5">Lieu de prise en charge</label>
                                <input type="text" name="lieu_prise" value="{{ old('lieu_prise') }}" placeholder="Ex : Aéroport, domicile, hôtel…" class="{{ $field('lieu_prise') }}">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-pslb-ink mb-1.5">Précisions</label>
                                <textarea name="message" rows="3" placeholder="Événement, nombre de passagers, trajet prévu…" class="{{ $field('message') }} resize-none">{{ old('message') }}</textarea>
                            </div>

                            @php
                                $a = session('loc_captcha_a', rand(1, 9));
                                $b = session('loc_captcha_b', rand(1, 9));
                                session(['loc_captcha_a' => $a, 'loc_captcha_b' => $b]);
                            @endphp
                            <div>
                                <label class="block text-sm font-medium text-pslb-ink mb-1.5">
                                    Vérification anti-robot : combien font <strong>{{ $a }} + {{ $b }}</strong> ?
                                    <span class="text-red-500">*</span>
                                </label>
                                <input type="number" name="captcha" required placeholder="Votre réponse" class="{{ $field('captcha') }}">
                            </div>

                            <button type="submit"
                                class="w-full bg-pslb-blue-800 hover:bg-pslb-cyan-500 text-white py-3.5 rounded-xl font-semibold text-sm transition-all duration-300 hover:shadow-lg hover:-translate-y-0.5 flex items-center justify-center gap-2 group">
                                Envoyer ma demande de réservation
                                <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none"
                                    stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </turbo-frame>

    {{-- ==================== CTA FINAL ==================== --}}
    <section class="py-20 bg-gradient-to-r from-pslb-blue-800 to-pslb-blue-700 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-64 h-64 bg-pslb-cyan-500/10 rounded-full blur-3xl -translate-y-1/2 translate-x-1/2"></div>
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="max-w-3xl mx-auto text-center" data-aos="fade-up">
                <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">
                    Besoin d'un véhicule <span class="text-pslb-cyan-500">sur-mesure ?</span>
                </h2>
                <p class="text-white/70 mb-8 max-w-xl mx-auto">
                    Longue durée, flotte pour un événement, transfert : parlons de votre besoin.
                </p>
                <div class="flex flex-col sm:flex-row justify-center gap-4">
                    <a href="{{ CONTACT_PSLB_LINK }}"
                        class="bg-pslb-cyan-500 hover:bg-white hover:text-pslb-blue-800 text-white px-8 py-3 rounded-full text-lg font-semibold shadow-xl transition-colors">Appeler PSLB</a>
                    <a href="{{ route('contact') }}"
                        class="border border-white/40 hover:bg-white/10 text-white px-8 py-3 rounded-full text-lg font-semibold transition-colors">Nous contacter</a>
                </div>
            </div>
        </div>
    </section>

    {{-- ==================== SCRIPT FILTRES ==================== --}}
    <script>
        (() => {
            const cards = [...document.querySelectorAll('.vehicule-card')];
            const btns = [...document.querySelectorAll('[data-filter]')];
            const empty = document.getElementById('no-vehicule');

            const setFilter = (id) => {
                btns.forEach(b => {
                    const on = b.dataset.filter === id;
                    b.classList.toggle('bg-pslb-blue-800', on);
                    b.classList.toggle('text-white', on);
                    b.classList.toggle('bg-white', !on);
                    b.classList.toggle('text-pslb-blue-800', !on);
                });
                let n = 0;
                cards.forEach(c => {
                    const ok = id === 'all' || c.dataset.category === id;
                    c.classList.toggle('hidden', !ok);
                    if (ok) n++;
                });
                empty?.classList.toggle('hidden', n > 0);
                window.AOS?.refresh();
            };

            btns.forEach(b => b.addEventListener('click', () => setFilter(b.dataset.filter)));

            // « Réserver » sur une carte : présélectionne le véhicule dans le formulaire
            document.querySelectorAll('[data-reserve]').forEach(a => a.addEventListener('click', () => {
                const select = document.getElementById('vehicule_id');
                if (select) select.value = a.dataset.reserve;
            }));

            document.addEventListener('turbo:frame-load', () => window.AOS?.refreshHard());

            // Empeche une date de fin anterieure a la date de debut
            document.addEventListener('change', (e) => {
                if (e.target.name !== 'date_debut') return;
                const fin = document.querySelector('[name=date_fin]');
                fin.min = e.target.value;
                if (fin.value && fin.value < e.target.value) fin.value = e.target.value;
            });
        })();
    </script>
@endsection
