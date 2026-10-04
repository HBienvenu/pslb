<!DOCTYPE html>
<html lang="fr" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PSLB — Votre besoin, notre solution</title>
    <meta name="description"
        content="PSLB : location de voiture, livraison de colis et courses, couverture médiatique d'événements. Votre besoin, notre solution.">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">
    @vite(['resources/css/site.css', 'resources/js/site.js'])
    <style>
        .hero-bg {
            background: url('https://images.unsplash.com/photo-1542282088-72c9c27ed0cd?auto=format&fit=crop&w=1600&q=80') center/cover
        }

        section[id] {
            scroll-margin-top: 80px
        }

        @media (prefers-reduced-motion:reduce) {
            html {
                scroll-behavior: auto
            }
        }
    </style>
</head>

<body class="font-pslb overflow-x-hidden text-pslb-ink bg-white antialiased">

    <!-- ==================== NAVBAR ==================== -->
    <header id="siteHeader"
        class="fixed top-0 inset-x-0 z-50 bg-white/95 backdrop-blur border-b border-pslb-blue-800/10 transition-shadow duration-300">
        <div id="scrollBar" class="absolute bottom-0 left-0 h-[3px] w-0 bg-pslb-cyan-500"></div>
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
            <a href="#accueil" class="flex items-center">
                <img src="{{ asset('images/pslb-logo.png') }}" alt="PSLB — Votre besoin, notre solution"
                    class="h-12 w-auto">
            </a>
            <nav class="hidden lg:flex items-center gap-8 text-sm font-semibold text-pslb-blue-800">
                <a href="#accueil" class="hover:text-pslb-cyan-500 transition-colors">Accueil</a>
                <a href="#location" class="hover:text-pslb-cyan-500 transition-colors">Location de voiture</a>
                <a href="#livraison" class="hover:text-pslb-cyan-500 transition-colors">Livraison &amp; courses</a>
                <a href="#media" class="hover:text-pslb-cyan-500 transition-colors">Couverture médiatique</a>
                <a href="#apropos" class="hover:text-pslb-cyan-500 transition-colors">À propos</a>
                <a href="#contact"
                    class="bg-pslb-blue-800 hover:bg-pslb-cyan-500 text-white px-5 py-2.5 rounded-full transition-colors">Nous
                    contacter</a>
            </nav>
            <button id="menuBtn" class="lg:hidden p-2 text-pslb-blue-800" aria-label="Ouvrir le menu"
                aria-expanded="false">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" d="M4 7h16M4 12h16M4 17h16" />
                </svg>
            </button>
        </div>
        <nav id="mobileMenu"
            class="hidden lg:hidden bg-white border-t border-pslb-border px-4 py-4 space-y-1 text-sm font-semibold text-pslb-blue-800">
            <a href="#accueil" class="block py-2.5">Accueil</a>
            <a href="#location" class="block py-2.5">Location de voiture</a>
            <a href="#livraison" class="block py-2.5">Livraison &amp; courses</a>
            <a href="#media" class="block py-2.5">Couverture médiatique</a>
            <a href="#apropos" class="block py-2.5">À propos</a>
            <a href="#contact" class="block mt-2 text-center bg-pslb-blue-800 text-white py-3 rounded-full">Nous
                contacter</a>
        </nav>
    </header>

    <main>

        <!-- ==================== HERO ==================== -->
        <section id="accueil" class="hero-bg relative pt-36 pb-28 md:pt-48 md:pb-36 overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-br from-pslb-blue-900/90 to-pslb-blue-800/75"></div>
            <div class="container relative z-10 mx-auto px-4 sm:px-6 lg:px-8">
                <div class="max-w-3xl">
                    <div data-aos="fade-down"
                        class="inline-flex items-center gap-2 mb-6 bg-white/10 border border-white/15 rounded-lg px-4 py-2">
                        <span class="w-2 h-2 bg-pslb-cyan-500 rounded-full animate-pulse"></span>
                        <span class="text-white text-xs md:text-sm font-bold uppercase tracking-wide">PSLB
                            Entreprise</span>
                    </div>
                    <h1 data-aos="fade-up" data-aos-delay="100"
                        class="text-4xl md:text-6xl font-extrabold text-white leading-[1.05] tracking-tight">
                        Votre besoin.<br><span class="text-pslb-cyan-500">Notre solution.</span>
                    </h1>
                    <p data-aos="fade-up" data-aos-delay="200"
                        class="text-lg md:text-xl text-gray-200 mt-6 max-w-2xl font-medium">
                        Location de voiture · Livraison de colis et courses · Couverture médiatique<br
                            class="hidden md:block">
                        Trois services réunis dans une seule entreprise, pour les particuliers comme pour les
                        professionnels.
                    </p>
                    <div data-aos="fade-up" data-aos-delay="300" class="flex flex-col sm:flex-row gap-4 mt-10">
                        <a href="#services"
                            class="bg-pslb-cyan-500 hover:bg-white hover:text-pslb-blue-800 text-white px-8 py-3.5 rounded-full font-semibold shadow-xl text-center transition-colors">Découvrir
                            nos services</a>
                        <a href="#contact"
                            class="border border-white/40 hover:bg-white/10 text-white px-8 py-3.5 rounded-full font-semibold text-center transition-colors">Demander
                            un devis</a>
                    </div>
                    <nav data-aos="fade" data-aos-delay="450" class="mt-8 flex flex-wrap items-center gap-x-8 gap-y-3"
                        aria-label="Accès rapide">
                        <a href="#location"
                            class="text-xs font-semibold uppercase tracking-[0.2em] text-gray-200 hover:text-white">Louer
                            un véhicule</a>
                        <a href="#livraison"
                            class="text-xs font-semibold uppercase tracking-[0.2em] text-gray-200 hover:text-white">Envoyer
                            un colis</a>
                        <a href="#media"
                            class="text-xs font-semibold uppercase tracking-[0.2em] text-gray-200 hover:text-white">Couvrir
                            un événement</a>
                    </nav>
                </div>
            </div>
        </section>

        <!-- ==================== TRIPLE POSITIONNEMENT ==================== -->
        <section id="services" class="py-20 bg-pslb-surface">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                <div data-aos="fade-up" class="text-center max-w-3xl mx-auto mb-14">
                    <span class="text-pslb-cyan-500 font-semibold tracking-wider uppercase text-sm">Nos activités</span>
                    <h2 class="text-3xl md:text-4xl font-bold text-pslb-blue-800 mt-2">Trois services, <span
                            class="text-pslb-cyan-500">une même exigence</span></h2>
                    <div class="w-24 h-1 bg-pslb-cyan-500 mx-auto mt-5 rounded-full"></div>
                    <p class="text-pslb-muted mt-6">PSLB répond aux besoins du quotidien comme à ceux des entreprises :
                        se déplacer, faire livrer, se faire voir.</p>
                </div>
                <div class="grid md:grid-cols-3 gap-8">
                    <article data-aos="fade-right" data-aos-delay="100"
                        class="bg-white rounded-2xl p-8 shadow-sm hover:shadow-lg hover:-translate-y-1 transition border-l-4 border-pslb-cyan-500">
                        <div class="w-16 h-16 bg-pslb-blue-800/10 rounded-xl flex items-center justify-center mb-5">
                            <svg class="w-8 h-8 text-pslb-cyan-500" fill="none" stroke="currentColor" stroke-width="1.8"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M5 17H3v-5l2-5h12l3 5v5h-2m-12 0h8M7 17a2 2 0 104 0m4 0a2 2 0 104 0M5 12h14" />
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-pslb-blue-800">Location de voiture</h3>
                        <p class="text-pslb-muted mt-3 leading-relaxed">Des véhicules adaptés à vos déplacements
                            personnels, professionnels et événementiels.</p>
                        <a href="#location" class="inline-flex text-pslb-cyan-500 font-medium mt-5 hover:underline">Voir
                            les offres →</a>
                    </article>
                    <article data-aos="fade-up" data-aos-delay="200"
                        class="bg-white rounded-2xl p-8 shadow-sm hover:shadow-lg hover:-translate-y-1 transition border-l-4 border-pslb-cyan-500">
                        <div class="w-16 h-16 bg-pslb-blue-800/10 rounded-xl flex items-center justify-center mb-5">
                            <svg class="w-8 h-8 text-pslb-cyan-500" fill="none" stroke="currentColor" stroke-width="1.8"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-pslb-blue-800">Livraison de colis &amp; courses</h3>
                        <p class="text-pslb-muted mt-3 leading-relaxed">Colis, documents, commandes et courses : nous
                            les acheminons avec simplicité et rapidité.</p>
                        <a href="#livraison"
                            class="inline-flex text-pslb-cyan-500 font-medium mt-5 hover:underline">Envoyer un colis
                            →</a>
                    </article>
                    <article data-aos="fade-left" data-aos-delay="300"
                        class="bg-white rounded-2xl p-8 shadow-sm hover:shadow-lg hover:-translate-y-1 transition border-l-4 border-pslb-cyan-500">
                        <div class="w-16 h-16 bg-pslb-blue-800/10 rounded-xl flex items-center justify-center mb-5">
                            <svg class="w-8 h-8 text-pslb-cyan-500" fill="none" stroke="currentColor" stroke-width="1.8"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M3 9a2 2 0 012-2h.93a2 2 0 001.66-.89l.82-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.66.89l.82 1.22a2 2 0 001.66.89H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                <circle cx="12" cy="13" r="3.5" />
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-pslb-blue-800">Couverture médiatique</h3>
                        <p class="text-pslb-muted mt-3 leading-relaxed">Photos, vidéos, interviews, podcasts : nous
                            donnons de la visibilité à vos événements et projets.</p>
                        <a href="#media"
                            class="inline-flex text-pslb-cyan-500 font-medium mt-5 hover:underline">Découvrir →</a>
                    </article>
                </div>
            </div>
        </section>

        <!-- ==================== LOCATION DE VOITURE ==================== -->
        <section id="location" class="py-20 bg-white">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid lg:grid-cols-2 gap-14 items-center">
                    <div data-aos="fade-right" class="h-80 lg:h-[480px] rounded-3xl bg-cover bg-center shadow-2xl"
                        style="background-image:linear-gradient(135deg,rgba(0,59,92,.15),rgba(0,180,230,.05)),url('https://images.unsplash.com/photo-1550355291-bbee04a92027?auto=format&fit=crop&w=1100&q=80')"
                        role="img" aria-label="Voiture de location PSLB"></div>
                    <div data-aos="fade-left">
                        <span class="text-pslb-cyan-500 font-semibold uppercase tracking-wide text-sm">Mobilité</span>
                        <h2 class="text-3xl md:text-4xl font-bold text-pslb-blue-800 mt-1">Location de <span
                                class="text-pslb-cyan-500">voiture</span></h2>
                        <div class="w-16 h-1 bg-pslb-cyan-500 rounded-full mt-3"></div>
                        <p class="text-pslb-muted mt-5 leading-relaxed">Pour un jour, une semaine ou une mission longue
                            durée, choisissez le véhicule qui correspond à votre déplacement et à votre budget.</p>
                        <div class="grid sm:grid-cols-2 gap-4 mt-8">
                            <div data-aos="zoom-in" data-aos-delay="100"
                                class="rounded-xl border border-pslb-border bg-pslb-surface p-5">
                                <h3 class="font-bold text-pslb-blue-800 text-sm">Déplacements personnels</h3>
                                <p class="text-pslb-muted text-xs mt-1">Courses, famille, voyages, rendez-vous.</p>
                            </div>
                            <div data-aos="zoom-in" data-aos-delay="200"
                                class="rounded-xl border border-pslb-border bg-pslb-surface p-5">
                                <h3 class="font-bold text-pslb-blue-800 text-sm">Déplacements professionnels</h3>
                                <p class="text-pslb-muted text-xs mt-1">Missions, visites clients, accueil de
                                    partenaires.</p>
                            </div>
                            <div data-aos="zoom-in" data-aos-delay="300"
                                class="rounded-xl border border-pslb-border bg-pslb-surface p-5">
                                <h3 class="font-bold text-pslb-blue-800 text-sm">Événements</h3>
                                <p class="text-pslb-muted text-xs mt-1">Mariages, cérémonies, séminaires, lancements.
                                </p>
                            </div>
                            <div data-aos="zoom-in" data-aos-delay="400"
                                class="rounded-xl border border-pslb-border bg-pslb-surface p-5">
                                <h3 class="font-bold text-pslb-blue-800 text-sm">Avec ou sans chauffeur</h3>
                                <p class="text-pslb-muted text-xs mt-1">Selon votre besoin, à préciser lors de la
                                    demande.</p>
                            </div>
                        </div>
                        <a href="#contact"
                            class="inline-flex mt-8 bg-pslb-blue-800 hover:bg-pslb-cyan-500 text-white px-8 py-3 rounded-full font-semibold transition-colors">Réserver
                            un véhicule</a>
                    </div>
                </div>
            </div>
        </section>

        <!-- ==================== LIVRAISON DE COLIS & COURSES ==================== -->
        <section id="livraison" class="py-20 bg-pslb-surface">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid lg:grid-cols-2 gap-14 items-center">
                    <div data-aos="fade-left"
                        class="lg:order-2 h-80 lg:h-[480px] rounded-3xl bg-cover bg-center shadow-2xl"
                        style="background-image:linear-gradient(135deg,rgba(0,59,92,.15),rgba(0,180,230,.05)),url('https://images.unsplash.com/photo-1580674684081-7617fbf3d745?auto=format&fit=crop&w=1100&q=80')"
                        role="img" aria-label="Livraison de colis PSLB"></div>
                    <div data-aos="fade-right" class="lg:order-1">
                        <span class="text-pslb-cyan-500 font-semibold uppercase tracking-wide text-sm">Logistique</span>
                        <h2 class="text-3xl md:text-4xl font-bold text-pslb-blue-800 mt-1">Livraison de colis <span
                                class="text-pslb-cyan-500">&amp; courses</span></h2>
                        <div class="w-16 h-1 bg-pslb-cyan-500 rounded-full mt-3"></div>
                        <p class="text-pslb-muted mt-5 leading-relaxed">Vous n'avez pas le temps de vous déplacer ? Nous
                            récupérons et livrons à votre place, là où vous en avez besoin.</p>
                        <ul class="mt-8 space-y-4">
                            <li data-aos="fade-up" data-aos-delay="100" class="flex gap-4"><span
                                    class="shrink-0 w-10 h-10 rounded-lg bg-pslb-cyan-500/10 text-pslb-cyan-500 flex items-center justify-center font-bold">✓</span>
                                <div>
                                    <h3 class="font-bold text-pslb-blue-800 text-sm">Colis</h3>
                                    <p class="text-pslb-muted text-xs">Envoi et réception de colis, de la prise en
                                        charge à la remise.</p>
                                </div>
                            </li>
                            <li data-aos="fade-up" data-aos-delay="200" class="flex gap-4"><span
                                    class="shrink-0 w-10 h-10 rounded-lg bg-pslb-cyan-500/10 text-pslb-cyan-500 flex items-center justify-center font-bold">✓</span>
                                <div>
                                    <h3 class="font-bold text-pslb-blue-800 text-sm">Documents</h3>
                                    <p class="text-pslb-muted text-xs">Dépôt et retrait de dossiers, courriers et plis
                                        importants.</p>
                                </div>
                            </li>
                            <li data-aos="fade-up" data-aos-delay="300" class="flex gap-4"><span
                                    class="shrink-0 w-10 h-10 rounded-lg bg-pslb-cyan-500/10 text-pslb-cyan-500 flex items-center justify-center font-bold">✓</span>
                                <div>
                                    <h3 class="font-bold text-pslb-blue-800 text-sm">Commandes</h3>
                                    <p class="text-pslb-muted text-xs">Livraison de vos achats et commandes à domicile
                                        ou au bureau.</p>
                                </div>
                            </li>
                            <li data-aos="fade-up" data-aos-delay="400" class="flex gap-4"><span
                                    class="shrink-0 w-10 h-10 rounded-lg bg-pslb-cyan-500/10 text-pslb-cyan-500 flex items-center justify-center font-bold">✓</span>
                                <div>
                                    <h3 class="font-bold text-pslb-blue-800 text-sm">Courses</h3>
                                    <p class="text-pslb-muted text-xs">Un achat ou une démarche à faire ? Dites-nous
                                        quoi et où.</p>
                                </div>
                            </li>
                        </ul>
                        <a href="#contact"
                            class="inline-flex mt-8 bg-pslb-blue-800 hover:bg-pslb-cyan-500 text-white px-8 py-3 rounded-full font-semibold transition-colors">Demander
                            une livraison</a>
                    </div>
                </div>
            </div>
        </section>

        <!-- ==================== COUVERTURE MÉDIATIQUE ==================== -->
        <section id="media" class="py-20 bg-pslb-blue-800 text-white relative overflow-hidden">
            <div
                class="absolute -right-64 -top-52 w-[500px] h-[500px] rounded-full border border-pslb-cyan-500/25 pointer-events-none">
            </div>
            <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
                <div class="grid lg:grid-cols-5 gap-12 items-start">
                    <div data-aos="fade-right" class="lg:col-span-2">
                        <span
                            class="text-pslb-cyan-500 font-semibold uppercase tracking-wide text-sm">Communication</span>
                        <h2 class="text-3xl md:text-4xl font-bold mt-1">Faites vivre <span
                                class="text-pslb-cyan-500">vos événements</span></h2>
                        <div class="w-16 h-1 bg-pslb-cyan-500 rounded-full mt-3"></div>
                        <p class="text-white/70 mt-5 leading-relaxed">De la captation à la diffusion, nous transformons
                            vos lancements, cérémonies et actualités en contenus qui attirent l'attention.</p>
                        <a href="#contact"
                            class="inline-flex mt-8 bg-pslb-cyan-500 hover:bg-white hover:text-pslb-blue-800 text-white px-8 py-3 rounded-full font-semibold transition-colors">Couvrir
                            mon événement</a>
                    </div>
                    <div class="lg:col-span-3 grid sm:grid-cols-2 gap-4">
                        <div data-aos="fade-up" data-aos-delay="0"
                            class="p-6 rounded-xl border border-white/10 bg-white/5 hover:bg-pslb-cyan-500/15 hover:border-pslb-cyan-500/40 transition">
                            <div class="text-2xl mb-3">📸</div>
                            <h3 class="font-bold text-sm">Photos du lancement</h3>
                            <p class="text-white/55 text-xs mt-1">Capturer les moments importants de votre événement.
                            </p>
                        </div>
                        <div data-aos="fade-up" data-aos-delay="80"
                            class="p-6 rounded-xl border border-white/10 bg-white/5 hover:bg-pslb-cyan-500/15 hover:border-pslb-cyan-500/40 transition">
                            <div class="text-2xl mb-3">🎬</div>
                            <h3 class="font-bold text-sm">Vidéo de l'événement</h3>
                            <p class="text-white/55 text-xs mt-1">Un film qui prolonge la vie de votre événement.</p>
                        </div>
                        <div data-aos="fade-up" data-aos-delay="160"
                            class="p-6 rounded-xl border border-white/10 bg-white/5 hover:bg-pslb-cyan-500/15 hover:border-pslb-cyan-500/40 transition">
                            <div class="text-2xl mb-3">🎤</div>
                            <h3 class="font-bold text-sm">Interviews</h3>
                            <p class="text-white/55 text-xs mt-1">Mettre vos acteurs et invités au premier plan.</p>
                        </div>
                        <div data-aos="fade-up" data-aos-delay="240"
                            class="p-6 rounded-xl border border-white/10 bg-white/5 hover:bg-pslb-cyan-500/15 hover:border-pslb-cyan-500/40 transition">
                            <div class="text-2xl mb-3">🎙️</div>
                            <h3 class="font-bold text-sm">Podcast</h3>
                            <p class="text-white/55 text-xs mt-1">Donner une voix à vos idées et à votre expertise.</p>
                        </div>
                        <div data-aos="fade-up" data-aos-delay="320"
                            class="p-6 rounded-xl border border-white/10 bg-white/5 hover:bg-pslb-cyan-500/15 hover:border-pslb-cyan-500/40 transition">
                            <div class="text-2xl mb-3">📱</div>
                            <h3 class="font-bold text-sm">Publications sur les réseaux sociaux</h3>
                            <p class="text-white/55 text-xs mt-1">Transformer votre événement en contenus à partager.
                            </p>
                        </div>
                        <div data-aos="fade-up" data-aos-delay="400"
                            class="p-6 rounded-xl border border-white/10 bg-white/5 hover:bg-pslb-cyan-500/15 hover:border-pslb-cyan-500/40 transition">
                            <div class="text-2xl mb-3">📣</div>
                            <h3 class="font-bold text-sm">Promotion sponsorisée</h3>
                            <p class="text-white/55 text-xs mt-1">Amplifier votre visibilité auprès d'un public ciblé.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- ==================== COMMENT ÇA MARCHE ==================== -->
        <section class="py-20 bg-white">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                <div data-aos="fade-up" class="text-center max-w-2xl mx-auto mb-14">
                    <span class="text-pslb-cyan-500 font-semibold uppercase tracking-wide text-sm">Simple et
                        rapide</span>
                    <h2 class="text-3xl md:text-4xl font-bold text-pslb-blue-800 mt-2">Comment <span
                            class="text-pslb-cyan-500">ça marche</span> ?</h2>
                    <div class="w-16 h-1 bg-pslb-cyan-500 rounded-full mt-4 mx-auto"></div>
                </div>
                <div class="grid md:grid-cols-3 gap-8">
                    <div data-aos="fade-up" data-aos-delay="100" class="text-center px-4">
                        <div
                            class="w-14 h-14 mx-auto rounded-full bg-pslb-blue-800 text-white flex items-center justify-center text-xl font-extrabold">
                            1</div>
                        <h3 class="font-bold text-pslb-blue-800 mt-4">Vous nous contactez</h3>
                        <p class="text-pslb-muted text-sm mt-2">Par téléphone ou WhatsApp, décrivez votre besoin :
                            véhicule, livraison ou couverture.</p>
                    </div>
                    <div data-aos="fade-up" data-aos-delay="250" class="text-center px-4">
                        <div
                            class="w-14 h-14 mx-auto rounded-full bg-pslb-blue-800 text-white flex items-center justify-center text-xl font-extrabold">
                            2</div>
                        <h3 class="font-bold text-pslb-blue-800 mt-4">Nous étudions votre demande</h3>
                        <p class="text-pslb-muted text-sm mt-2">Nous vous proposons une solution adaptée et un tarif
                            clair avant tout engagement.</p>
                    </div>
                    <div data-aos="fade-up" data-aos-delay="400" class="text-center px-4">
                        <div
                            class="w-14 h-14 mx-auto rounded-full bg-pslb-blue-800 text-white flex items-center justify-center text-xl font-extrabold">
                            3</div>
                        <h3 class="font-bold text-pslb-blue-800 mt-4">Nous passons à l'action</h3>
                        <p class="text-pslb-muted text-sm mt-2">Le véhicule est prêt, le colis est livré, l'événement
                            est couvert.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- ==================== POURQUOI PSLB ==================== -->
        <section id="apropos" class="py-20 bg-pslb-surface">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid lg:grid-cols-2 gap-14 items-center">
                    <div data-aos="fade-right">
                        <span class="text-pslb-cyan-500 font-semibold uppercase tracking-wide text-sm">Pourquoi PSLB
                            ?</span>
                        <h2 class="text-3xl md:text-4xl font-bold text-pslb-blue-800 mt-1">Un seul partenaire pour <span
                                class="text-pslb-cyan-500">vos besoins</span></h2>
                        <div class="w-16 h-1 bg-pslb-cyan-500 rounded-full mt-3"></div>
                        <p class="text-pslb-muted mt-5 leading-relaxed">PSLB réunit mobilité, logistique et
                            communication au sein d'une même entreprise afin de simplifier votre quotidien.</p>
                    </div>
                    <div class="space-y-4">
                        <div data-aos="fade-left" data-aos-delay="100"
                            class="flex gap-4 bg-white rounded-2xl p-5 border border-pslb-border border-l-4 border-l-pslb-blue-800 shadow-sm">
                            <div
                                class="shrink-0 w-11 h-11 rounded-xl bg-pslb-blue-800/10 text-pslb-blue-800 flex items-center justify-center font-bold">
                                ✓</div>
                            <div>
                                <h3 class="font-bold text-pslb-blue-800 text-sm">Une approche professionnelle</h3>
                                <p class="text-pslb-muted text-xs mt-1">Des prestations pensées pour les attentes des
                                    particuliers et des entreprises.</p>
                            </div>
                        </div>
                        <div data-aos="fade-left" data-aos-delay="200"
                            class="flex gap-4 bg-white rounded-2xl p-5 border border-pslb-border border-l-4 border-l-pslb-cyan-500 shadow-sm">
                            <div
                                class="shrink-0 w-11 h-11 rounded-xl bg-pslb-cyan-500/10 text-pslb-cyan-500 flex items-center justify-center font-bold">
                                ◆</div>
                            <div>
                                <h3 class="font-bold text-pslb-blue-800 text-sm">Plusieurs solutions réunies</h3>
                                <p class="text-pslb-muted text-xs mt-1">Un seul interlocuteur pour vous déplacer, livrer
                                    et communiquer.</p>
                            </div>
                        </div>
                        <div data-aos="fade-left" data-aos-delay="300"
                            class="flex gap-4 bg-white rounded-2xl p-5 border border-pslb-border border-l-4 border-l-pslb-blue-800 shadow-sm">
                            <div
                                class="shrink-0 w-11 h-11 rounded-xl bg-pslb-blue-800/10 text-pslb-blue-800 flex items-center justify-center font-bold">
                                →</div>
                            <div>
                                <h3 class="font-bold text-pslb-blue-800 text-sm">Une réponse adaptée</h3>
                                <p class="text-pslb-muted text-xs mt-1">Chaque demande est étudiée pour proposer la
                                    solution qui lui correspond.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- ==================== CTA / CONTACT ==================== -->
        <section id="contact" class="py-20 bg-white">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                <div data-aos="zoom-in"
                    class="rounded-3xl bg-gradient-to-r from-pslb-blue-800 to-pslb-blue-700 p-10 md:p-16 flex flex-col md:flex-row md:items-center justify-between gap-8 relative overflow-hidden">
                    <div
                        class="absolute -right-24 -top-44 w-80 h-80 rounded-full border border-white/10 pointer-events-none">
                    </div>
                    <div class="relative">
                        <h2 class="text-3xl font-bold text-white">Un besoin ? Parlons-en.</h2>
                        <p class="text-white/70 mt-3 max-w-lg">Location, livraison ou couverture médiatique : PSLB est
                            là pour vous accompagner.</p>
                    </div>
                    <div class="relative flex flex-col sm:flex-row gap-3">
                        <a href="tel:+22900000000"
                            class="bg-pslb-cyan-500 hover:bg-white hover:text-pslb-blue-800 text-white px-8 py-3.5 rounded-full font-semibold text-center whitespace-nowrap transition-colors">Appeler
                            PSLB</a>
                        <a href="https://wa.me/22900000000" target="_blank" rel="noopener"
                            class="border border-white/40 hover:bg-white/10 text-white px-8 py-3.5 rounded-full font-semibold text-center whitespace-nowrap transition-colors">Écrire
                            sur WhatsApp</a>
                    </div>
                </div>
            </div>
        </section>

    </main>

    <!-- ==================== FOOTER ==================== -->
    <footer class="bg-pslb-blue-900 text-white pt-14 pb-6">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div data-aos="fade-up" class="grid md:grid-cols-3 gap-10 pb-10">
                <div>
                    <img src="{{ asset('images/pslb-logo.png') }}" alt="PSLB"
                        class="h-12 w-auto mb-4 brightness-0 invert">
                    <p class="text-white/55 text-xs max-w-xs leading-relaxed">PSLB — Votre besoin, notre solution.
                        Mobilité, livraison et couverture médiatique réunies au sein d'une même entreprise.</p>
                </div>
                <div>
                    <h4 class="font-bold text-sm mb-4">Nos activités</h4>
                    <div class="grid gap-2.5 text-xs text-white/55">
                        <a href="#location" class="hover:text-pslb-cyan-500">Location de voiture</a>
                        <a href="#livraison" class="hover:text-pslb-cyan-500">Livraison de colis &amp; courses</a>
                        <a href="#media" class="hover:text-pslb-cyan-500">Couverture médiatique</a>
                    </div>
                </div>
                <div>
                    <h4 class="font-bold text-sm mb-4">Navigation</h4>
                    <div class="grid gap-2.5 text-xs text-white/55">
                        <a href="#accueil" class="hover:text-pslb-cyan-500">Accueil</a>
                        <a href="#services" class="hover:text-pslb-cyan-500">Services</a>
                        <a href="#apropos" class="hover:text-pslb-cyan-500">À propos</a>
                        <a href="#contact" class="hover:text-pslb-cyan-500">Contact</a>
                    </div>
                </div>
            </div>
            <div
                class="border-t border-white/10 pt-5 flex flex-col sm:flex-row justify-between gap-2 text-[11px] text-white/40">
                <span>© {{ date('Y') }} PSLB Entreprise. Tous droits réservés.</span>
                <span>Votre besoin, notre solution</span>
            </div>
        </div>
    </footer>

    <script>
        const header = document.getElementById('siteHeader'), bar = document.getElementById('scrollBar');
        const onScroll = () => {
            const h = document.documentElement;
            const p = h.scrollTop / (h.scrollHeight - h.clientHeight || 1);
            bar.style.width = (p * 100) + '%';
            header.classList.toggle('shadow-md', h.scrollTop > 10);
        };
        window.addEventListener('scroll', onScroll, { passive: true });
        onScroll();
    </script>

    <script>
        const btn = document.getElementById('menuBtn'), menu = document.getElementById('mobileMenu');
        btn.addEventListener('click', () => {
            const open = menu.classList.toggle('hidden') === false;
            btn.setAttribute('aria-expanded', open);
        });
        menu.querySelectorAll('a').forEach(a => a.addEventListener('click', () => { menu.classList.add('hidden'); btn.setAttribute('aria-expanded', false); }));
    </script>
</body>

</html>
