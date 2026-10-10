@extends('layouts.site.app', ['title' => 'À propos'])

@section('content')
    @php
        // Image de fond du hero : visuel automobile plus cohérent avec la thématique du site.
        $heroImg = "https://images.unsplash.com/photo-1492144534655-ae79c964c9d7?auto=format&fit=crop&w=1600&q=80";

        // Liens vers les pages de services (adaptez les noms de routes si besoin ; '#' si la route n'existe pas encore)
        $lienLocation = Route::has('location') ? route('location') : '#';
        $lienCouverture = Route::has('couverture') ? route('couverture') : '#';
        $lienContact = Route::has('contact') ? route('contact') : '#';

        $check = '<path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75"/>';

        $activites = [
            [
                'title' => 'Location de voitures',
                'text' => 'Des véhicules pour vos déplacements personnels, professionnels ou vos événements, avec ou sans chauffeur.',
                'link' => $lienLocation,
                'cta' => 'Voir nos véhicules',
                'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M8 17h8M8 17a2 2 0 11-4 0 2 2 0 014 0zm12 0a2 2 0 11-4 0 2 2 0 014 0zM3 13l2-6a2 2 0 011.9-1.4h10.2A2 2 0 0119 7l2 6M3 13v4h1m17-4v4h-1M3 13h18"/>',
            ],
            [
                'title' => 'Livraison & courses',
                'text' => 'Un colis à déposer, un document à récupérer, une course à faire : nous nous en chargeons pour vous faire gagner du temps.',
                'link' => $lienContact,
                'cta' => 'Nous en parler',
                'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>',
            ],
            [
                'title' => 'Couverture médiatique',
                'text' => 'Photos, vidéos, interviews et podcasts : nous donnons de la visibilité à vos événements et à vos projets.',
                'link' => $lienCouverture,
                'cta' => 'Découvrir le service',
                'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>',
            ],
        ];

        $valeurs = [
            [
                'title' => 'Proximité',
                'text' => 'Un interlocuteur qui vous répond, qui vous connaît et qui comprend votre besoin.',
                'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>',
            ],
            [
                'title' => 'Réactivité',
                'text' => 'Chaque demande est traitée avec sérieux, et nous revenons vers vous rapidement.',
                'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>',
            ],
            [
                'title' => 'Transparence',
                'text' => 'Des tarifs et des conditions annoncés clairement, avant de vous engager.',
                'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>',
            ],
            [
                'title' => 'Soin du détail',
                'text' => 'Un véhicule propre et prêt, une livraison faite dans les règles, un contenu soigné : le détail compte.',
                'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>',
            ],
        ];

        $etapes = [
            ['Vous nous exposez votre besoin', 'Par le formulaire du site, par téléphone ou par message : dites-nous simplement ce qu\'il vous faut.', 'bg-pslb-cyan-500'],
            ['Nous vous répondons clairement', 'Disponibilité, tarif, délai : vous recevez une proposition claire, sans mauvaise surprise.', 'bg-pslb-blue-800'],
            ['Nous réalisons, et restons joignables', 'Le service est assuré comme convenu, et nous restons à votre écoute jusqu\'au bout.', 'bg-pslb-cyan-500'],
        ];
    @endphp

    {{-- ==================== HERO ==================== --}}
    <section
        class="relative pt-32 pb-24 md:pt-44 md:pb-32 overflow-hidden bg-gradient-to-br from-pslb-blue-900 to-pslb-blue-700 {{ $heroImg ? 'bg-cover bg-center' : '' }}"
        @if ($heroImg) style="background-image:url('{{ $heroImg }}')" @endif>
        @if ($heroImg)
            <div class="absolute inset-0 bg-gradient-to-br from-pslb-blue-900/90 to-pslb-blue-800/70"></div>
        @else
            <div class="absolute -top-24 -right-24 w-96 h-96 bg-pslb-cyan-500/20 rounded-full blur-3xl"></div>
            <div class="absolute bottom-0 -left-24 w-80 h-80 bg-pslb-cyan-500/10 rounded-full blur-3xl"></div>
        @endif
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="max-w-4xl mx-auto">
                <div data-aos="fade-down"
                    class="inline-flex items-center gap-2 bg-white/10 backdrop-blur-sm rounded-full px-4 py-1.5 mb-6">
                    <span class="w-2 h-2 bg-pslb-cyan-500 rounded-full animate-pulse"></span>
                    <span class="text-white text-sm font-medium uppercase tracking-widest">À propos</span>
                </div>
                <h1 data-aos="fade-up" data-aos-delay="100"
                    class="text-4xl md:text-5xl lg:text-6xl font-extrabold text-white leading-tight">
                    Des services pensés<br><span class="text-pslb-cyan-500">pour vous simplifier la vie</span>
                </h1>
                <p data-aos="fade-up" data-aos-delay="200" class="text-lg md:text-xl text-gray-200 mt-6 max-w-2xl">
                    PSLB est une jeune entreprise basée à {{ LOCALISATION_PSLB }}. Nous vous aidons à vous déplacer, à faire livrer ou
                    effectuer vos courses, et à donner de la visibilité à vos événements.
                </p>
                <div data-aos="fade-up" data-aos-delay="300" class="flex flex-col sm:flex-row gap-4 mt-10">
                    <a href="#activites"
                        class="bg-pslb-cyan-500 hover:bg-white hover:text-pslb-blue-800 text-white px-8 py-3 rounded-full text-lg font-semibold shadow-xl inline-flex items-center justify-center gap-2 group transition-colors">
                        Découvrir nos activités
                        <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 7l5 5m0 0l-5 5m5-5H6" />
                        </svg>
                    </a>
                    <a href="{{ $lienContact }}"
                        class="border border-white/40 hover:bg-white/10 text-white px-8 py-3 rounded-full text-lg font-semibold inline-flex items-center justify-center transition-colors">
                        Nous contacter
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

    {{-- ==================== QUI SOMMES-NOUS ==================== --}}
    <section class="py-20 bg-white overflow-hidden">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-2 gap-14 items-center max-w-5xl mx-auto">
                <div data-aos="fade-right">
                    <span class="text-pslb-cyan-500 font-semibold tracking-wider uppercase text-sm">Qui sommes-nous</span>
                    <h2 class="text-3xl md:text-4xl font-bold text-pslb-blue-800 mt-2">
                        Une entreprise <span class="text-pslb-cyan-500">qui démarre</span>, avec de vraies ambitions
                    </h2>
                    <div class="w-24 h-1 bg-pslb-cyan-500 mt-5 rounded-full mb-6"></div>
                    <div class="space-y-4 text-pslb-muted leading-relaxed">
                        {{-- À personnaliser : ce que signifie PSLB, l'histoire du projet, le ou les fondateurs --}}
                        <p>
                            PSLB est née d'une idée simple : rassembler, au même endroit, des services concrets qui font
                            gagner du temps et ouvrent des portes. Se déplacer sans contrainte, confier une livraison
                            ou une course, faire connaître un événement : trois besoins du quotidien, une même exigence.
                        </p>
                        <p>
                            Nous sommes une entreprise récente, et nous l'assumons : c'est ce qui nous rend attentifs à
                            chaque client. Chaque demande compte pour nous, et nous construisons notre réputation un
                            service après l'autre.
                        </p>
                    </div>
                </div>

                <div class="bg-pslb-surface rounded-2xl p-8 border-l-4 border-pslb-blue-800 shadow-sm" data-aos="fade-left">
                    <h3 class="text-lg font-bold text-pslb-blue-800 mb-5">Notre mission</h3>
                    <p class="text-pslb-ink leading-relaxed mb-6">
                        Rendre les déplacements, les livraisons et la communication événementielle plus simples, plus
                        fiables et plus accessibles pour les particuliers comme pour les professionnels.
                    </p>
                    <ul class="space-y-3">
                        @foreach (['Des services clairs, faciles à demander', 'Des réponses rapides et des tarifs annoncés', 'Une équipe joignable avant, pendant et après'] as $item)
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

    {{-- ==================== NOS ACTIVITÉS ==================== --}}
    <section id="activites" class="py-20 bg-pslb-surface overflow-hidden scroll-mt-24">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16" data-aos="fade-up">
                <span class="text-pslb-cyan-500 font-semibold tracking-wider uppercase text-sm">Nos activités</span>
                <h2 class="text-3xl md:text-4xl font-bold text-pslb-blue-800 mt-2">
                    Trois services, <span class="text-pslb-cyan-500">une seule équipe</span>
                </h2>
                <div class="w-24 h-1 bg-pslb-cyan-500 mx-auto mt-5 rounded-full"></div>
            </div>
            <div class="grid md:grid-cols-3 gap-6">
                @foreach ($activites as $i => $a)
                    <div class="bg-white rounded-2xl p-8 shadow-sm card-hover border-l-4 border-pslb-cyan-500 flex flex-col"
                        data-aos="fade-up" data-aos-delay="{{ $i * 100 }}">
                        <div class="w-14 h-14 bg-pslb-blue-800/10 rounded-xl flex items-center justify-center mb-5">
                            <svg class="w-7 h-7 text-pslb-cyan-500" fill="none" stroke="currentColor" stroke-width="1.8"
                                viewBox="0 0 24 24">{!! $a['icon'] !!}</svg>
                        </div>
                        <h3 class="text-xl font-bold text-pslb-blue-800">{{ $a['title'] }}</h3>
                        <p class="text-pslb-muted text-sm mt-3 leading-relaxed flex-1">{{ $a['text'] }}</p>
                        <a href="{{ $a['link'] }}"
                            class="inline-flex items-center gap-1.5 mt-6 text-sm font-semibold text-pslb-blue-800 hover:text-pslb-cyan-500 transition-colors group">
                            {{ $a['cta'] }}
                            <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none"
                                stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                            </svg>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ==================== NOS VALEURS ==================== --}}
    <section class="py-20 bg-white overflow-hidden">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16" data-aos="fade-up">
                <span class="text-pslb-cyan-500 font-semibold tracking-wider uppercase text-sm">Nos engagements</span>
                <h2 class="text-3xl md:text-4xl font-bold text-pslb-blue-800 mt-2">
                    Ce qui guide <span class="text-pslb-cyan-500">notre façon de travailler</span>
                </h2>
                <div class="w-24 h-1 bg-pslb-cyan-500 mx-auto mt-5 rounded-full"></div>
            </div>
            <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach ($valeurs as $i => $v)
                    <div class="text-center bg-pslb-surface rounded-2xl p-7" data-aos="fade-up" data-aos-delay="{{ $i * 100 }}">
                        <div class="w-14 h-14 bg-pslb-cyan-500/10 rounded-full flex items-center justify-center mx-auto mb-5">
                            <svg class="w-7 h-7 text-pslb-cyan-500" fill="none" stroke="currentColor" stroke-width="1.8"
                                viewBox="0 0 24 24">{!! $v['icon'] !!}</svg>
                        </div>
                        <h3 class="text-lg font-bold text-pslb-blue-800">{{ $v['title'] }}</h3>
                        <p class="text-pslb-muted text-sm mt-2 leading-relaxed">{{ $v['text'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ==================== COMMENT NOUS TRAVAILLONS ==================== --}}
    <section class="py-20 bg-pslb-surface overflow-hidden">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-14" data-aos="fade-up">
                <span class="text-pslb-cyan-500 font-semibold tracking-wider uppercase text-sm">Notre méthode</span>
                <h2 class="text-3xl md:text-4xl font-bold text-pslb-blue-800 mt-2">
                    Simple, <span class="text-pslb-cyan-500">du début à la fin</span>
                </h2>
                <div class="w-24 h-1 bg-pslb-cyan-500 mx-auto mt-5 rounded-full"></div>
            </div>
            <div class="grid md:grid-cols-3 gap-8 relative max-w-5xl mx-auto">
                <div
                    class="hidden md:block absolute top-10 left-[16.6%] right-[16.6%] border-t-2 border-dashed border-pslb-cyan-500/30 z-0">
                </div>
                @foreach ($etapes as $i => [$title, $desc, $color])
                    <div class="text-center relative z-10" data-aos="fade-up" data-aos-delay="{{ $i * 150 }}">
                        <div
                            class="w-20 h-20 {{ $color }} rounded-full flex items-center justify-center mx-auto mb-6 shadow-lg">
                            <span class="text-white text-2xl font-extrabold">{{ sprintf('%02d', $i + 1) }}</span>
                        </div>
                        <h3 class="text-xl font-bold text-pslb-blue-800 mb-3">{{ $title }}</h3>
                        <p class="text-pslb-muted text-sm leading-relaxed">{{ $desc }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ==================== CTA FINAL ==================== --}}
    <section class="py-20 bg-gradient-to-r from-pslb-blue-800 to-pslb-blue-700 relative overflow-hidden">
        <div
            class="absolute top-0 right-0 w-64 h-64 bg-pslb-cyan-500/10 rounded-full blur-3xl -translate-y-1/2 translate-x-1/2">
        </div>
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="max-w-3xl mx-auto text-center" data-aos="fade-up">
                <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">
                    Faisons connaissance, <span class="text-pslb-cyan-500">parlons de votre besoin</span>
                </h2>
                <p class="text-white/70 mb-8 max-w-xl mx-auto">
                    Une question, un projet, une demande particulière ? Écrivez-nous ou appelez-nous : nous vous
                    répondons avec plaisir.
                </p>
                <div class="flex flex-col sm:flex-row justify-center gap-4">
                    <a href="{{ CONTACT_PSLB_LINK }}"
                        class="bg-pslb-cyan-500 hover:bg-white hover:text-pslb-blue-800 text-white px-8 py-3 rounded-full text-lg font-semibold shadow-xl transition-colors">Appeler
                        PSLB</a>
                    <a href="{{ $lienContact }}"
                        class="border border-white/40 hover:bg-white/10 text-white px-8 py-3 rounded-full text-lg font-semibold transition-colors">Nous
                        contacter</a>
                </div>
            </div>
        </div>
    </section>
@endsection
