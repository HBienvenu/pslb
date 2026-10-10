@extends('layouts.site.app', ['title' => 'Couverture médiatique'])

@section('content')
    @php
        $input = 'w-full px-4 py-3 rounded-xl border border-pslb-border bg-pslb-surface text-pslb-ink placeholder-gray-400 text-sm focus:outline-none focus:border-pslb-blue-800 focus:ring-2 focus:ring-pslb-blue-800/20 transition-all';
        $field = fn($name) => $input . ($errors->has($name) ? ' !border-red-400' : '');
        $check = '<path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75"/>';

        // Présentation des services (textes à adapter à votre offre réelle)
        $prestations = [
            [
                'key' => 'photo',
                'title' => 'Photos',
                'text' => 'Reportage photo de vos événements : moments forts, portraits, ambiance et détails, livrés prêts à publier.',
                'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>',
            ],
            [
                'key' => 'video',
                'title' => 'Vidéos',
                'text' => 'Captation et montage : résumé d\'événement, teaser, vidéo de présentation de votre projet ou de votre structure.',
                'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>',
            ],
            [
                'key' => 'interview',
                'title' => 'Interviews',
                'text' => 'Interviews de vos dirigeants, intervenants ou témoins, en vidéo ou en audio, pour raconter votre histoire.',
                'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>',
            ],
            [
                'key' => 'podcast',
                'title' => 'Podcasts',
                'text' => 'Enregistrement et production d\'épisodes : prise de son, montage et mise en forme pour votre diffusion.',
                'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"/>',
            ],
        ];
    @endphp

    {{-- ==================== HERO ==================== --}}
    @php
        // Photo libre de droits (Unsplash) : opérateur filmant une scène aux lumières colorées.
        // À terme, remplacez-la par une photo de vos propres couvertures (ex : asset('images/couverture-hero.jpg')).
        $heroImg = 'https://images.unsplash.com/photo-1765344550361-ef69dddc1b76?auto=format&fit=crop&w=1600&q=80';
    @endphp

    <section class="relative pt-32 pb-24 md:pt-44 md:pb-32 overflow-hidden bg-pslb-blue-900 bg-cover bg-center"
        style="background-image:url('{{ $heroImg }}')">

        {{-- Voile bleu pour garder le texte lisible --}}
        <div class="absolute inset-0 bg-gradient-to-br from-pslb-blue-900/90 to-pslb-blue-800/70"></div>

        <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="max-w-4xl mx-auto">
                <div data-aos="fade-down"
                    class="inline-flex items-center gap-2 bg-white/10 backdrop-blur-sm rounded-full px-4 py-1.5 mb-6">
                    <span class="w-2 h-2 bg-pslb-cyan-500 rounded-full animate-pulse"></span>
                    <span class="text-white text-sm font-medium uppercase tracking-widest">Couverture médiatique</span>
                </div>
                <h1 data-aos="fade-up" data-aos-delay="100"
                    class="text-4xl md:text-5xl lg:text-6xl font-extrabold text-white leading-tight">
                    Donnez de la visibilité<br><span class="text-pslb-cyan-500">à vos événements et projets</span>
                </h1>
                <p data-aos="fade-up" data-aos-delay="200" class="text-lg md:text-xl text-gray-200 mt-6 max-w-2xl">
                    Photos, vidéos, interviews, podcasts : nous couvrons vos moments importants et vous livrons des contenus
                    prêts à être partagés.
                </p>
                <div data-aos="fade-up" data-aos-delay="300" class="flex flex-col sm:flex-row gap-4 mt-10">
                    <a href="#demande"
                        class="bg-pslb-cyan-500 hover:bg-white hover:text-pslb-blue-800 text-white px-8 py-3 rounded-full text-lg font-semibold shadow-xl inline-flex items-center justify-center gap-2 group transition-colors">
                        Nous confier votre événement
                        <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 7l5 5m0 0l-5 5m5-5H6" />
                        </svg>
                    </a>
                    <a href="#services"
                        class="border border-white/40 hover:bg-white/10 text-white px-8 py-3 rounded-full text-lg font-semibold inline-flex items-center justify-center transition-colors">
                        Découvrir nos services
                    </a>
                </div>
            </div>
        </div>

        {{-- Vague de transition vers la section suivante --}}
        <div class="absolute bottom-0 left-0 w-full">
            <svg viewBox="0 0 1200 120" preserveAspectRatio="none" class="relative block w-full h-12 md:h-16">
                <path
                    d="M0,0V5.63C149.93,59,314.09,71.32,475.83,42.57c77.44-13.74,149.51-38.5,229.58-45.86C870.13-9.08,1049.16,21.06,1200,39.66V0Z"
                    fill="white" />
            </svg>
        </div>
    </section>

    {{-- ==================== SERVICES ==================== --}}
    <section id="services" class="py-20 bg-white overflow-hidden scroll-mt-24">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16" data-aos="fade-up">
                <span class="text-pslb-cyan-500 font-semibold tracking-wider uppercase text-sm">Ce que nous faisons</span>
                <h2 class="text-3xl md:text-4xl font-bold text-pslb-blue-800 mt-2">
                    Quatre façons de <span class="text-pslb-cyan-500">raconter votre histoire</span>
                </h2>
                <div class="w-24 h-1 bg-pslb-cyan-500 mx-auto mt-5 rounded-full"></div>
            </div>
            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach ($prestations as $i => $s)
                    <div class="bg-pslb-surface rounded-2xl p-7 shadow-sm card-hover border-l-4 border-pslb-cyan-500"
                        data-aos="fade-up" data-aos-delay="{{ $i * 100 }}">
                        <div class="w-14 h-14 bg-pslb-blue-800/10 rounded-xl flex items-center justify-center mb-5">
                            <svg class="w-7 h-7 text-pslb-cyan-500" fill="none" stroke="currentColor" stroke-width="1.8"
                                viewBox="0 0 24 24">{!! $s['icon'] !!}</svg>
                        </div>
                        <h3 class="text-lg font-bold text-pslb-blue-800">{{ $s['title'] }}</h3>
                        <p class="text-pslb-muted text-sm mt-2 leading-relaxed">{{ $s['text'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ==================== POUR QUELS ÉVÉNEMENTS ==================== --}}
    <section class="py-20 bg-pslb-surface overflow-hidden">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-2 gap-12 items-center max-w-5xl mx-auto">
                <div data-aos="fade-right">
                    <span class="text-pslb-cyan-500 font-semibold tracking-wider uppercase text-sm">Pour qui ?</span>
                    <h2 class="text-3xl md:text-4xl font-bold text-pslb-blue-800 mt-2">
                        Chaque événement mérite <span class="text-pslb-cyan-500">d'être vu</span>
                    </h2>
                    <div class="w-24 h-1 bg-pslb-cyan-500 mt-5 rounded-full mb-6"></div>
                    <p class="text-pslb-muted leading-relaxed">
                        Entreprises, associations, institutions, églises ou particuliers : si vous organisez quelque chose
                        qui compte, nous vous aidons à en garder une trace et à le faire rayonner.
                    </p>
                </div>
                <div class="bg-white rounded-2xl p-8 shadow-sm border-l-4 border-pslb-blue-800" data-aos="fade-left">
                    <h3 class="text-lg font-bold text-pslb-blue-800 mb-4">Quelques exemples</h3>
                    <ul class="grid sm:grid-cols-2 gap-3">
                        @foreach (['Conférences et séminaires', 'Lancements de produits', 'Cérémonies et mariages', 'Concerts et spectacles', 'Projets associatifs', 'Présentations d\'entreprise'] as $item)
                            <li class="flex items-start gap-2.5 text-sm text-pslb-ink">
                                <svg class="w-4 h-4 text-pslb-cyan-500 shrink-0 mt-0.5" fill="none" stroke="currentColor"
                                    stroke-width="2.5" viewBox="0 0 24 24">{!! $check !!}</svg>
                                {{ $item }}
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </section>

    {{-- ==================== PROCESSUS ==================== --}}
    <section class="py-20 bg-white overflow-hidden">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-14" data-aos="fade-up">
                <span class="text-pslb-cyan-500 font-semibold tracking-wider uppercase text-sm">Comment ça se passe</span>
                <h2 class="text-3xl md:text-4xl font-bold text-pslb-blue-800 mt-2">
                    Un processus <span class="text-pslb-cyan-500">simple en 4 étapes</span>
                </h2>
                <div class="w-24 h-1 bg-pslb-cyan-500 mx-auto mt-5 rounded-full"></div>
            </div>
            @php
                $steps = [
                    ['Parlez-nous de votre besoin', 'Remplissez le formulaire : événement, date, lieu et contenus souhaités.', 'bg-pslb-cyan-500'],
                    ['Recevez une proposition', 'Nous vous recontactons avec un devis adapté à votre projet.', 'bg-pslb-blue-800'],
                    ['Nous couvrons l\'événement', 'Notre équipe se déplace et réalise la couverture convenue.', 'bg-pslb-blue-800'],
                    ['Recevez vos contenus', 'Photos, vidéos ou épisodes vous sont livrés, prêts à être partagés.', 'bg-pslb-cyan-500'],
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
        </div>
    </section>

    {{-- ==================== DEMANDE ==================== --}}
    <turbo-frame id="demande-couverture">
        <section id="demande" class="py-20 bg-pslb-surface overflow-hidden scroll-mt-24">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid lg:grid-cols-2 gap-16 items-start">

                    <div data-aos="fade-right">
                        <span class="text-pslb-cyan-500 font-semibold tracking-wider uppercase text-sm">Votre demande</span>
                        <h2 class="text-3xl md:text-4xl font-bold text-pslb-blue-800 mt-2">
                            Parlez-nous <span class="text-pslb-cyan-500">de votre événement</span>
                        </h2>
                        <div class="w-24 h-1 bg-pslb-cyan-500 mt-5 rounded-full mb-8"></div>
                        <p class="text-pslb-muted leading-relaxed mb-8">
                            Décrivez-nous votre besoin : nous vous recontactons avec une proposition adaptée.
                            Plus vous nous donnez de détails, plus notre réponse sera précise.
                        </p>
                        <div class="space-y-5">
                            @foreach ([['Téléphone', '+229 00 00 00 00'], ['WhatsApp', '+229 00 00 00 00'], ['Adresse', 'Cotonou, Bénin']] as [$label, $value])
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

                        <form method="POST" action="{{ route('media.demande.store') }}"
                            data-turbo-frame="demande-couverture" class="space-y-5">
                            @csrf
                            <input type="text" name="website" value="" style="display:none" tabindex="-1" autocomplete="off">

                            <div class="grid sm:grid-cols-2 gap-5">
                                <div>
                                    <label for="cm-nom" class="block text-sm font-medium text-pslb-ink mb-1.5">Nom complet <span class="text-red-500">*</span></label>
                                    <input type="text" id="cm-nom" name="nom" value="{{ old('nom') }}" required placeholder="Jean Dupont" class="{{ $field('nom') }}">
                                </div>
                                <div>
                                    <label for="cm-tel" class="block text-sm font-medium text-pslb-ink mb-1.5">Téléphone <span class="text-red-500">*</span></label>
                                    <input type="tel" id="cm-tel" name="telephone" value="{{ old('telephone') }}" required placeholder="+229 XX XX XX XX" class="{{ $field('telephone') }}">
                                </div>
                            </div>

                            <div class="grid sm:grid-cols-2 gap-5">
                                <div>
                                    <label for="cm-email" class="block text-sm font-medium text-pslb-ink mb-1.5">Email</label>
                                    <input type="email" id="cm-email" name="email" value="{{ old('email') }}" placeholder="votre@email.com" class="{{ $field('email') }}">
                                </div>
                                <div>
                                    <label for="cm-org" class="block text-sm font-medium text-pslb-ink mb-1.5">Organisation</label>
                                    <input type="text" id="cm-org" name="organisation" value="{{ old('organisation') }}" placeholder="Entreprise, association…" class="{{ $field('organisation') }}">
                                </div>
                            </div>

                            <div>
                                <label for="cm-titre" class="block text-sm font-medium text-pslb-ink mb-1.5">Nom de l'événement ou du projet <span class="text-red-500">*</span></label>
                                <input type="text" id="cm-titre" name="titre_evenement" value="{{ old('titre_evenement') }}" required placeholder="Ex : Conférence annuelle, lancement de produit…" class="{{ $field('titre_evenement') }}">
                            </div>

                            <div>
                                <label for="cm-lieu" class="block text-sm font-medium text-pslb-ink mb-1.5">Lieu</label>
                                <input type="text" id="cm-lieu" name="lieu" value="{{ old('lieu') }}" placeholder="Ex : Cotonou, Palais des congrès" class="{{ $field('lieu') }}">
                            </div>

                            <div class="grid sm:grid-cols-2 gap-5">
                                <div>
                                    <label for="cm-debut" class="block text-sm font-medium text-pslb-ink mb-1.5">Date <span class="text-red-500">*</span></label>
                                    <input type="date" id="cm-debut" name="date_debut" value="{{ old('date_debut') }}" min="{{ now()->toDateString() }}" required class="{{ $field('date_debut') }}">
                                </div>
                                <div>
                                    <label for="cm-fin" class="block text-sm font-medium text-pslb-ink mb-1.5">Date de fin <span class="text-xs font-normal text-pslb-muted">(si plusieurs jours)</span></label>
                                    <input type="date" id="cm-fin" name="date_fin" value="{{ old('date_fin') }}" min="{{ now()->toDateString() }}" class="{{ $field('date_fin') }}">
                                </div>
                            </div>

                            {{-- Services souhaités --}}
                            <div>
                                <p class="block text-sm font-medium text-pslb-ink mb-2">Services souhaités <span class="text-red-500">*</span></p>
                                <div class="grid grid-cols-2 gap-2.5">
                                    @foreach ($services as $key => $label)
                                        <label
                                            class="flex items-center gap-2.5 px-4 py-3 rounded-xl border bg-pslb-surface text-sm text-pslb-ink cursor-pointer transition-all hover:border-pslb-cyan-500/60 has-[:checked]:border-pslb-cyan-500 has-[:checked]:bg-pslb-cyan-500/10 {{ $errors->has('services') ? 'border-red-400' : 'border-pslb-border' }}">
                                            <input type="checkbox" name="services[]" value="{{ $key }}"
                                                class="accent-pslb-cyan-500 w-4 h-4" @checked(in_array($key, old('services', [])))>
                                            {{ $label }}
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            <div>
                                <label for="cm-message" class="block text-sm font-medium text-pslb-ink mb-1.5">Description du besoin</label>
                                <textarea id="cm-message" name="message" rows="3" placeholder="Programme, nombre d'invités, contenus attendus, usage prévu (réseaux sociaux, site, archives)…" class="{{ $field('message') }} resize-none">{{ old('message') }}</textarea>
                            </div>

                            @php
                                $a = session('media_captcha_a', rand(1, 9));
                                $b = session('media_captcha_b', rand(1, 9));
                                session(['media_captcha_a' => $a, 'media_captcha_b' => $b]);
                            @endphp
                            <div>
                                <label for="cm-captcha" class="block text-sm font-medium text-pslb-ink mb-1.5">
                                    Vérification anti-robot : combien font <strong>{{ $a }} + {{ $b }}</strong> ?
                                    <span class="text-red-500">*</span>
                                </label>
                                <input type="number" id="cm-captcha" name="captcha" required placeholder="Votre réponse" class="{{ $field('captcha') }}">
                            </div>

                            <button type="submit"
                                class="w-full bg-pslb-blue-800 hover:bg-pslb-cyan-500 text-white py-3.5 rounded-xl font-semibold text-sm transition-all duration-300 hover:shadow-lg hover:-translate-y-0.5 flex items-center justify-center gap-2 group">
                                Envoyer ma demande
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
                    Un projet <span class="text-pslb-cyan-500">sur-mesure ?</span>
                </h2>
                <p class="text-white/70 mb-8 max-w-xl mx-auto">
                    Campagne sur plusieurs événements, série de podcasts, contenus réguliers : parlons-en.
                </p>
                <div class="flex flex-col sm:flex-row justify-center gap-4">
                    <a href="tel:+22900000000"
                        class="bg-pslb-cyan-500 hover:bg-white hover:text-pslb-blue-800 text-white px-8 py-3 rounded-full text-lg font-semibold shadow-xl transition-colors">Appeler PSLB</a>
                    <a href="{{ route('contact') }}"
                        class="border border-white/40 hover:bg-white/10 text-white px-8 py-3 rounded-full text-lg font-semibold transition-colors">Nous contacter</a>
                </div>
            </div>
        </div>
    </section>

    <script>
        // Évite de lier les écouteurs plusieurs fois avec la navigation Turbo
        if (!window.__couvertureBound) {
            window.__couvertureBound = true;

            // La date de fin ne peut pas précéder la date de début
            document.addEventListener('change', (e) => {
                if (e.target.name !== 'date_debut') return;
                const fin = e.target.form?.querySelector('[name=date_fin]');
                if (!fin) return;
                fin.min = e.target.value;
                if (fin.value && fin.value < e.target.value) fin.value = e.target.value;
            });

            // Après l'envoi du formulaire (turbo-frame), réactive les animations AOS
            document.addEventListener('turbo:frame-load', () => window.AOS?.refreshHard());
        }
    </script>
@endsection
