
@extends('layouts.site.app', ['title' => 'Solutions numériques'])

@section('content')
    @php
        $input = 'w-full px-4 py-3 rounded-lg border border-pslb-border bg-white text-pslb-blue-800 placeholder-gray-400 text-sm focus:outline-none focus:border-pslb-blue-800 focus:ring-2 focus:ring-pslb-blue-800/20 transition-colors';
        $field = fn($name) => $input . ($errors->has($name) ? ' !border-red-400' : '');
        $label = 'block text-sm font-medium text-pslb-ink mb-1.5';

        $solutions = [
            [
                'numero' => '01',
                'icone' => 'globe',
                'titre' => 'Création de sites web',
                'description' => 'Présentez votre entreprise, vos activités et vos services avec un site web professionnel, moderne et adapté aux mobiles.',
                'points' => [
                    'Sites vitrines et institutionnels',
                    'Sites d’entreprise et catalogues',
                    'Sites administrables',
                    'Boutiques en ligne',
                ],
            ],
            [
                'numero' => '02',
                'icone' => 'code',
                'titre' => 'Applications web sur mesure',
                'description' => 'Digitalisez vos opérations grâce à des outils conçus autour de vos méthodes de travail et des besoins de vos équipes.',
                'points' => [
                    'Gestion des clients et des prospects',
                    'Gestion de stocks et de facturation',
                    'Automatisation des tâches',
                    'Tableaux de bord et rapports',
                ],
            ],
            [
                'numero' => '03',
                'icone' => 'users',
                'titre' => 'Plateformes et espaces membres',
                'description' => 'Créez un environnement numérique dans lequel vos utilisateurs peuvent s’inscrire, accéder à des contenus et utiliser vos services.',
                'points' => [
                    'Comptes utilisateurs et profils',
                    'Plateformes de formation',
                    'Espaces privés et tableaux de bord',
                    'Intégration de paiements selon les besoins',
                ],
            ],
            [
                'numero' => '04',
                'icone' => 'settings',
                'titre' => 'Maintenance et évolution',
                'description' => 'Faites évoluer votre site ou votre application existante pour maintenir son bon fonctionnement et accompagner votre croissance.',
                'points' => [
                    'Correction des anomalies',
                    'Mises à jour techniques',
                    'Sauvegardes et suivi',
                    'Ajout de nouvelles fonctionnalités',
                ],
            ],
        ];

        $etapes = [
            [
                'numero' => '01',
                'titre' => 'Écoute et analyse',
                'description' => 'Nous échangeons sur vos objectifs, vos difficultés, vos utilisateurs et les résultats attendus.',
            ],
            [
                'numero' => '02',
                'titre' => 'Conception et proposition',
                'description' => 'Nous définissons les fonctionnalités, la structure, les priorités et le périmètre du projet.',
            ],
            [
                'numero' => '03',
                'titre' => 'Développement et tests',
                'description' => 'Nous réalisons la solution, vérifions son fonctionnement et vous permettons de valider les résultats.',
            ],
            [
                'numero' => '04',
                'titre' => 'Mise en ligne et accompagnement',
                'description' => 'Nous préparons la mise en production et pouvons assurer la maintenance ainsi que les évolutions convenues.',
            ],
        ];

        $faq = [
            [
                'question' => 'Combien coûte la création d’un site web ?',
                'reponse' => 'Le coût dépend du type de site, du nombre de pages, des fonctionnalités, du contenu à produire et des éventuelles intégrations. Après analyse de votre besoin, nous pouvons vous proposer un devis adapté.',
            ],
            [
                'question' => 'Pouvez-vous reprendre un site déjà existant ?',
                'reponse' => 'Oui. Après une analyse technique, nous pouvons intervenir sur un site existant pour corriger des problèmes, améliorer son affichage, renforcer sa sécurité ou ajouter de nouvelles fonctionnalités.',
            ],
            [
                'question' => 'Pourrai-je modifier moi-même le contenu de mon site ?',
                'reponse' => 'C’est possible. Nous pouvons prévoir un espace d’administration permettant de modifier les textes, les images et les contenus concernés, selon les fonctionnalités retenues.',
            ],
            [
                'question' => 'Proposez-vous un accompagnement après la livraison ?',
                'reponse' => 'Oui, des prestations de maintenance et d’évolution peuvent être prévues selon vos besoins. Le périmètre, la fréquence et les conditions d’intervention sont définis dans la proposition.',
            ],
            [
                'question' => 'Comment démarrer un projet avec PSLB ?',
                'reponse' => 'Décrivez votre besoin à travers le formulaire de cette page. Nous pourrons ensuite échanger sur vos objectifs, les fonctionnalités attendues, le budget et le calendrier.',
            ],
        ];
    @endphp

    <main class="overflow-hidden">

        {{-- HERO --}}
        <section class="relative isolate overflow-hidden bg-pslb-blue-900 text-white">
            {{-- Image de fond --}}
            <div class="absolute inset-0 -z-20">
                <img
                    src="https://images.unsplash.com/photo-1498050108023-c5249f4df085?auto=format&fit=crop&w=1200&q=80"
                    alt="Développeur travaillant sur un projet informatique"
                    class="h-full w-full object-cover object-center"
                >
            </div>

            {{-- Voile sombre pour la lisibilité --}}
            <div class="absolute inset-0 -z-10 bg-gradient-to-r
                        from-pslb-blue-900/95 via-pslb-blue-900/80
                        to-pslb-blue-900/45">
            </div>

            <div class="mx-auto grid max-w-7xl items-center gap-12 px-4
                        py-20 sm:px-6 sm:py-24 lg:grid-cols-2 lg:px-8 lg:py-32">

                <div class="max-w-2xl">
                    <span class="inline-flex items-center rounded-full
                                border border-white/25 bg-white/10
                                px-4 py-2 text-sm font-medium text-cyan-200">
                        Innovation · Technologie · Performance
                    </span>

                    <h1 class="mt-6 text-4xl font-extrabold leading-tight
                            tracking-tight sm:text-5xl lg:text-6xl">
                        Des solutions numériques
                        <span class="text-cyan-300">
                            au service de votre performance
                        </span>
                    </h1>

                    <p class="mt-6 max-w-xl text-base leading-7 text-white/85 sm:text-lg">
                        Nous concevons et développons des solutions digitales
                        adaptées à vos besoins pour optimiser vos processus,
                        renforcer votre présence en ligne et accélérer votre
                        transformation numérique.
                    </p>

                    <div class="mt-8 flex flex-wrap gap-4">
                        <a href="#demande-projet"
                        class="inline-flex items-center justify-center rounded-lg
                                bg-cyan-400 px-6 py-3.5 font-semibold
                                text-pslb-blue-900 transition hover:bg-cyan-300">
                            Parler de mon projet
                            <span class="ml-2" aria-hidden="true">→</span>
                        </a>

                        <a href="#nos-solutions"
                        class="inline-flex items-center justify-center rounded-lg
                                border border-white/40 bg-white/5 px-6 py-3.5
                                font-semibold text-white transition
                                hover:bg-white/15">
                            Découvrir nos solutions
                        </a>
                    </div>
                </div>

                {{-- Bloc complémentaire --}}
                <div class="hidden lg:block">
                    <div class="ml-auto max-w-md rounded-2xl border
                                border-white/20 bg-white/10 p-8 shadow-2xl
                                backdrop-blur-sm">
                        <p class="text-sm font-semibold uppercase tracking-widest
                                text-cyan-200">
                            Notre engagement
                        </p>

                        <h2 class="mt-4 text-2xl font-bold">
                            La technologie pensée pour vos objectifs.
                        </h2>

                        <p class="mt-4 leading-7 text-white/80">
                            Des outils fiables, des interfaces intuitives et
                            des développements conçus pour évoluer avec votre activité.
                        </p>
                    </div>
                </div>

            </div>
        </section>

        {{-- INTRODUCTION --}}
        <section class="py-14 bg-white border-b border-pslb-border">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                <div class="max-w-3xl mx-auto text-center">
                    <p class="text-sm font-semibold uppercase tracking-widest text-pslb-muted">
                        Notre approche
                    </p>
                    <h2 class="text-2xl md:text-3xl font-bold text-pslb-blue-800 mt-3">
                        Le numérique doit répondre à un besoin concret.
                    </h2>
                    <p class="text-pslb-muted leading-relaxed mt-4">
                        Un bon outil ne se limite pas à son apparence. Il doit être facile
                        à utiliser, cohérent avec votre organisation et capable d’évoluer
                        avec votre activité.
                    </p>
                </div>
            </div>
        </section>

        {{-- SOLUTIONS --}}
        <section id="solutions" class="py-20 md:py-24 bg-pslb-surface scroll-mt-24">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                <div class="max-w-2xl mb-12">
                    <p class="text-sm font-semibold uppercase tracking-widest text-pslb-muted">
                        Nos domaines d’intervention
                    </p>
                    <h2 class="text-3xl md:text-4xl font-bold text-pslb-blue-800 mt-3">
                        Des solutions pour chaque étape de votre transformation numérique.
                    </h2>
                    <p class="text-pslb-muted mt-4 leading-relaxed">
                        Que vous souhaitiez créer un nouvel outil, améliorer votre présence
                        en ligne ou faire évoluer une plateforme existante, nous étudions
                        avec vous la solution la plus pertinente.
                    </p>
                </div>

                <div class="grid sm:grid-cols-2 gap-6">
                    @foreach ($solutions as $solution)
                        <article class="bg-white border border-pslb-border rounded-xl p-6 md:p-8 hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
                            <div class="flex items-center justify-between mb-6">
                                <span class="w-12 h-12 rounded-xl bg-pslb-blue-800/5 text-pslb-blue-800 flex items-center justify-center text-xl font-bold">
                                    @switch($solution['icone'])
                                        @case('globe') ◎ @break
                                        @case('code') &lt;/&gt; @break
                                        @case('users') ♧ @break
                                        @case('settings') ⚙ @break
                                    @endswitch
                                </span>
                                <span class="text-sm font-semibold text-pslb-muted">
                                    {{ $solution['numero'] }}
                                </span>
                            </div>

                            <h3 class="text-xl md:text-2xl font-bold text-pslb-blue-800">
                                {{ $solution['titre'] }}
                            </h3>

                            <p class="text-pslb-muted leading-relaxed mt-3">
                                {{ $solution['description'] }}
                            </p>

                            <ul class="mt-6 space-y-3">
                                @foreach ($solution['points'] as $point)
                                    <li class="flex items-start gap-3 text-sm text-pslb-ink">
                                        <span class="text-pslb-blue-800 font-bold" aria-hidden="true">✓</span>
                                        <span>{{ $point }}</span>
                                    </li>
                                @endforeach
                            </ul>

                            <a href="#demande"
                               class="inline-flex items-center gap-2 text-sm font-semibold text-pslb-blue-800 mt-7 hover:underline underline-offset-4">
                                Discuter de ce besoin <span aria-hidden="true">→</span>
                            </a>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>

        {{-- BENEFICES --}}
        <section class="py-20 bg-white">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid lg:grid-cols-12 gap-12 items-start">
                    <div class="lg:col-span-5">
                        <p class="text-sm font-semibold uppercase tracking-widest text-pslb-muted">
                            Pourquoi nous solliciter ?
                        </p>
                        <h2 class="text-3xl md:text-4xl font-bold text-pslb-blue-800 mt-3">
                            Une solution pensée pour votre réalité.
                        </h2>
                        <p class="text-pslb-muted leading-relaxed mt-5">
                            Chaque organisation a ses contraintes, ses utilisateurs et ses
                            objectifs. Nous privilégions une démarche structurée afin de
                            construire une solution cohérente avec vos priorités.
                        </p>

                        <a href="#demande"
                           class="inline-flex items-center gap-2 mt-7 font-semibold text-pslb-blue-800 hover:underline underline-offset-4">
                            Présenter mon projet <span aria-hidden="true">→</span>
                        </a>
                    </div>

                    <div class="lg:col-span-7 grid sm:grid-cols-2 gap-6">
                        <div class="border-l-2 border-pslb-blue-800 pl-5 py-2">
                            <h3 class="font-bold text-lg text-pslb-blue-800">
                                Adaptation à vos besoins
                            </h3>
                            <p class="text-sm text-pslb-muted mt-2 leading-relaxed">
                                Les fonctionnalités sont définies selon vos objectifs et non
                                ajoutées sans raison.
                            </p>
                        </div>

                        <div class="border-l-2 border-pslb-blue-800 pl-5 py-2">
                            <h3 class="font-bold text-lg text-pslb-blue-800">
                                Utilisation simplifiée
                            </h3>
                            <p class="text-sm text-pslb-muted mt-2 leading-relaxed">
                                Une navigation claire et une interface adaptée aux utilisateurs
                                concernés.
                            </p>
                        </div>

                        <div class="border-l-2 border-pslb-blue-800 pl-5 py-2">
                            <h3 class="font-bold text-lg text-pslb-blue-800">
                                Affichage responsive
                            </h3>
                            <p class="text-sm text-pslb-muted mt-2 leading-relaxed">
                                Une interface conçue pour s’adapter aux écrans d’ordinateur,
                                de tablette et de smartphone.
                            </p>
                        </div>

                        <div class="border-l-2 border-pslb-blue-800 pl-5 py-2">
                            <h3 class="font-bold text-lg text-pslb-blue-800">
                                Évolution dans le temps
                            </h3>
                            <p class="text-sm text-pslb-muted mt-2 leading-relaxed">
                                La possibilité de faire évoluer la solution en fonction de
                                vos nouveaux besoins.
                            </p>
                        </div>

                        <div class="border-l-2 border-pslb-blue-800 pl-5 py-2">
                            <h3 class="font-bold text-lg text-pslb-blue-800">
                                Attention à la sécurité
                            </h3>
                            <p class="text-sm text-pslb-muted mt-2 leading-relaxed">
                                Des mesures techniques adaptées au projet, notamment pour les
                                accès, les données et les mises à jour.
                            </p>
                        </div>

                        <div class="border-l-2 border-pslb-blue-800 pl-5 py-2">
                            <h3 class="font-bold text-lg text-pslb-blue-800">
                                Accompagnement
                            </h3>
                            <p class="text-sm text-pslb-muted mt-2 leading-relaxed">
                                Des échanges à chaque étape et des modalités de suivi définies
                                selon votre projet.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- PROCESSUS --}}
        <section class="py-20 md:py-24 bg-pslb-surface">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                <div class="max-w-2xl mx-auto text-center mb-14">
                    <p class="text-sm font-semibold uppercase tracking-widest text-pslb-muted">
                        Notre méthode
                    </p>
                    <h2 class="text-3xl md:text-4xl font-bold text-pslb-blue-800 mt-3">
                        Comment se déroule votre projet ?
                    </h2>
                    <p class="text-pslb-muted mt-4 leading-relaxed">
                        Un processus organisé pour clarifier les attentes, limiter les
                        incompréhensions et avancer avec des objectifs partagés.
                    </p>
                </div>

                <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach ($etapes as $etape)
                        <article class="relative bg-white border border-pslb-border rounded-xl p-6">
                            <span class="text-4xl font-bold text-pslb-blue-800/15">
                                {{ $etape['numero'] }}
                            </span>
                            <h3 class="text-lg font-bold text-pslb-blue-800 mt-4">
                                {{ $etape['titre'] }}
                            </h3>
                            <p class="text-sm text-pslb-muted leading-relaxed mt-3">
                                {{ $etape['description'] }}
                            </p>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>

        {{-- TECHNOLOGIES --}}
        <section class="py-16 md:py-20 bg-white">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                <div class="max-w-3xl mx-auto text-center">
                    <p class="text-sm font-semibold uppercase tracking-widest text-pslb-muted">
                        Notre approche technique
                    </p>
                    <h2 class="text-3xl font-bold text-pslb-blue-800 mt-3">
                        Des technologies choisies selon votre projet.
                    </h2>
                    <p class="text-pslb-muted leading-relaxed mt-4">
                        Le choix technique dépend des fonctionnalités attendues, du budget,
                        des contraintes d’hébergement, de la sécurité et de la facilité
                        de maintenance.
                    </p>

                    <div class="flex flex-wrap justify-center gap-3 mt-8">
                        @foreach (['Laravel', 'PHP', 'JavaScript', 'HTML5', 'CSS3', 'Tailwind CSS', 'MySQL', 'API'] as $tech)
                            <span class="px-4 py-2 rounded-full border border-pslb-border bg-pslb-surface text-sm font-medium text-pslb-blue-800">
                                {{ $tech }}
                            </span>
                        @endforeach
                    </div>

                    <p class="text-xs text-pslb-muted mt-5">
                        Les technologies sont sélectionnées en fonction des exigences de chaque projet.
                    </p>
                </div>
            </div>
        </section>

        {{-- FAQ --}}
        <section class="py-20 bg-pslb-surface">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                <div class="max-w-3xl mx-auto">
                    <div class="text-center mb-10">
                        <p class="text-sm font-semibold uppercase tracking-widest text-pslb-muted">
                            Questions fréquentes
                        </p>
                        <h2 class="text-3xl md:text-4xl font-bold text-pslb-blue-800 mt-3">
                            Vous souhaitez en savoir plus ?
                        </h2>
                    </div>

                    <div class="space-y-3">
                        @foreach ($faq as $index => $item)
                            <details class="group bg-white border border-pslb-border rounded-xl">
                                <summary class="flex cursor-pointer list-none items-center justify-between gap-4 p-5 font-semibold text-pslb-blue-800">
                                    <span>{{ $item['question'] }}</span>
                                    <span class="text-xl transition-transform group-open:rotate-45"
                                          aria-hidden="true">+</span>
                                </summary>

                                <div class="px-5 pb-5 text-sm text-pslb-muted leading-relaxed">
                                    {{ $item['reponse'] }}
                                </div>
                            </details>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>

        {{-- DEMANDE --}}
        <section id="demande" class="py-20 md:py-24 bg-white scroll-mt-24">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid lg:grid-cols-12 gap-12 items-start">

                    <div class="lg:col-span-5">
                        <p class="text-sm font-semibold uppercase tracking-widest text-pslb-muted">
                            Parlons de votre projet
                        </p>

                        <h2 class="text-3xl md:text-4xl font-bold text-pslb-blue-800 mt-3">
                            Quelle solution souhaitez-vous mettre en place ?
                        </h2>

                        <p class="text-pslb-muted leading-relaxed mt-5">
                            Expliquez-nous votre idée, votre besoin ou la difficulté que
                            vous souhaitez résoudre. Votre demande nous aidera à préparer
                            un premier échange adapté à votre projet.
                        </p>

                        <div class="mt-8 space-y-5">
                            <div>
                                <h3 class="text-sm font-semibold text-pslb-blue-800">
                                    Une idée encore à préciser ?
                                </h3>
                                <p class="text-sm text-pslb-muted mt-1">
                                    Vous pouvez décrire votre objectif sans avoir déjà défini
                                    toutes les fonctionnalités.
                                </p>
                            </div>

                            <div>
                                <h3 class="text-sm font-semibold text-pslb-blue-800">
                                    Un outil déjà existant ?
                                </h3>
                                <p class="text-sm text-pslb-muted mt-1">
                                    Indiquez les difficultés rencontrées et les améliorations
                                    que vous envisagez.
                                </p>
                            </div>

                            <div class="pt-5 border-t border-pslb-border">
                                <p class="text-sm text-pslb-muted">Vous pouvez également nous écrire</p>
                                <a href="mailto:{{ config('mail.contact', 'contact@pslb.com') }}"
                                   class="inline-block text-lg font-semibold text-pslb-blue-800 mt-1 hover:underline break-all">
                                    {{ config('mail.contact', 'contact@pslb.com') }}
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="lg:col-span-7 bg-pslb-surface rounded-2xl border border-pslb-border p-6 md:p-10">

                        @if (session('success'))
                            <div role="status"
                                 class="mb-6 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
                                {{ session('success') }}
                            </div>
                        @endif

                        @if ($errors->any())
                            <div role="alert"
                                 class="mb-6 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm">
                                <p class="text-red-700 font-semibold mb-2">
                                    Votre demande n’a pas pu être validée.
                                </p>
                                <ul class="list-disc list-inside text-red-600 space-y-1">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('formation.store') }}"
                              method="POST"
                              class="space-y-6">
                            @csrf

                            {{-- Champ piège antispam : doit aussi être vérifié côté serveur --}}
                            <div class="hidden" aria-hidden="true">
                                <label for="f-website">Ne pas remplir ce champ</label>
                                <input type="text" id="f-website" name="website"
                                       value="" tabindex="-1" autocomplete="off">
                            </div>

                            <div>
                                <h3 class="text-lg font-bold text-pslb-blue-800">
                                    Vos coordonnées
                                </h3>
                                <p class="text-sm text-pslb-muted mt-1">
                                    Les champs marqués d’un astérisque sont obligatoires.
                                </p>
                            </div>

                            <div class="grid md:grid-cols-2 gap-5">
                                <div>
                                    <label for="f-nom" class="{{ $label }}">
                                        Nom complet <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" id="f-nom" name="nom"
                                           value="{{ old('nom') }}"
                                           placeholder="Votre nom complet"
                                           autocomplete="name"
                                           maxlength="150"
                                           required
                                           class="{{ $field('nom') }}">
                                    @error('nom')
                                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="f-tel" class="{{ $label }}">
                                        Téléphone <span class="text-red-500">*</span>
                                    </label>
                                    <input type="tel" id="f-tel" name="telephone"
                                           value="{{ old('telephone') }}"
                                           placeholder="+229 XX XX XX XX"
                                           autocomplete="tel"
                                           maxlength="30"
                                           required
                                           class="{{ $field('telephone') }}">
                                    @error('telephone')
                                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div>
                                <label for="f-email" class="{{ $label }}">Adresse email</label>
                                <input type="email" id="f-email" name="email"
                                       value="{{ old('email') }}"
                                       placeholder="vous@entreprise.com"
                                       autocomplete="email"
                                       maxlength="255"
                                       class="{{ $field('email') }}">
                                @error('email')
                                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="border-t border-pslb-border pt-6">
                                <h3 class="text-lg font-bold text-pslb-blue-800">
                                    Votre projet
                                </h3>
                                <p class="text-sm text-pslb-muted mt-1">
                                    Quelques précisions nous aideront à mieux comprendre votre besoin.
                                </p>
                            </div>

                            <div>
                                <label for="f-service" class="{{ $label }}">
                                    Type de solution <span class="text-red-500">*</span>
                                </label>
                                <select id="f-service" name="service"
                                        required class="{{ $field('service') }}">
                                    <option value="">Sélectionnez une solution</option>
                                    @foreach (\App\Models\DemandeFormation::SERVICES as $key => $nom)
                                        <option value="{{ $key }}"
                                            @selected((string) old('service') === (string) $key)>
                                            {{ $nom }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('service')
                                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="grid md:grid-cols-2 gap-5">
                                <div>
                                    <label for="f-budget" class="{{ $label }}">
                                        Budget estimé
                                        <span class="text-xs font-normal text-pslb-muted">(FCFA)</span>
                                    </label>
                                    <input type="number" id="f-budget" name="budget"
                                           value="{{ old('budget') }}"
                                           min="0" step="1000"
                                           placeholder="Ex. : 250000"
                                           class="{{ $field('budget') }}">
                                    @error('budget')
                                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="f-date" class="{{ $label }}">
                                        Échéance souhaitée
                                    </label>
                                    <input type="date" id="f-date" name="date_prevue"
                                           value="{{ old('date_prevue') }}"
                                           min="{{ now()->toDateString() }}"
                                           class="{{ $field('date_prevue') }}">
                                    @error('date_prevue')
                                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div>
                                <label for="f-desc" class="{{ $label }}">
                                    Décrivez votre besoin
                                </label>
                                <textarea id="f-desc" name="description" rows="6"
                                          maxlength="5000"
                                          placeholder="Présentez votre activité, les difficultés rencontrées, les fonctionnalités souhaitées et les personnes qui utiliseront la solution..."
                                          class="{{ $field('description') }} resize-y">{{ old('description') }}</textarea>
                                <p class="text-xs text-pslb-muted mt-2">
                                    Vous pouvez aussi préciser si vous disposez déjà d’un site web
                                    ou d’une application.
                                </p>
                                @error('description')
                                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- CAPTCHA : les valeurs doivent être générées et conservées côté serveur --}}
                            @php
                                if (!session()->has('solution_captcha_a') || !session()->has('solution_captcha_b')) {
                                    session([
                                        'solution_captcha_a' => random_int(2, 9),
                                        'solution_captcha_b' => random_int(1, 8),
                                    ]);
                                }

                                $a = session('solution_captcha_a');
                                $b = session('solution_captcha_b');
                            @endphp

                            <div>
                                <label for="f-captcha" class="{{ $label }}">
                                    Vérification anti-robot : combien font
                                    <strong>{{ $a }} + {{ $b }}</strong> ?
                                    <span class="text-red-500">*</span>
                                </label>
                                <input type="number" id="f-captcha" name="captcha"
                                       placeholder="Votre réponse"
                                       required
                                       class="{{ $field('captcha') }} sm:max-w-[14rem]">
                                @error('captcha')
                                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <button type="submit"
                                    class="w-full inline-flex justify-center items-center gap-2 bg-pslb-blue-800 hover:bg-pslb-blue-900 text-white py-4 px-6 rounded-lg font-semibold transition-colors focus:outline-none focus:ring-2 focus:ring-pslb-blue-800 focus:ring-offset-2">
                                Envoyer ma demande
                                <span aria-hidden="true">→</span>
                            </button>

                            <p class="text-xs text-pslb-muted text-center leading-relaxed">
                                Les informations transmises servent à traiter votre demande
                                et à vous recontacter à ce sujet.
                            </p>
                        </form>
                    </div>
                </div>
            </div>
        </section>

        {{-- CTA FINAL --}}
        <section class="relative isolate overflow-hidden bg-gradient-to-br
                        from-pslb-blue-800 via-pslb-blue-700 to-cyan-700
                        text-white">
            {{-- Décoration --}}
            <div class="pointer-events-none absolute -right-24 -top-24
                        h-72 w-72 rounded-full bg-cyan-300/15 blur-3xl">
            </div>

            <div class="relative mx-auto max-w-7xl px-4 py-16 sm:px-6
                        sm:py-20 lg:px-8">
                <div class="flex flex-col items-start justify-between gap-8
                            md:flex-row md:items-center">

                    <div class="max-w-2xl">
                        <span class="text-sm font-semibold uppercase
                                    tracking-widest text-cyan-200">
                            Donnons vie à vos idées
                        </span>

                        <h2 class="mt-3 text-3xl font-bold tracking-tight
                                sm:text-4xl">
                            Prêt à concrétiser votre projet numérique ?
                        </h2>

                        <p class="mt-4 leading-7 text-white/85">
                            Échangeons sur vos besoins et identifions ensemble
                            la solution la plus adaptée à votre organisation.
                        </p>
                    </div>

                    <a href="#demande-projet"
                    class="inline-flex shrink-0 items-center justify-center
                            rounded-lg bg-white px-6 py-3.5 font-semibold
                            text-pslb-blue-900 shadow-lg transition
                            hover:bg-cyan-50">
                        Démarrer mon projet
                        <span class="ml-2" aria-hidden="true">→</span>
                    </a>

                </div>
            </div>
        </section>

    </main>
@endsection
