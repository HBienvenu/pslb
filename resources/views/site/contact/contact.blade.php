@extends('layouts.site.app', ['title' => 'Contact'])

@section('content')
    @php
        $heroImg = 'https://images.unsplash.com/photo-1550355291-bbee04a92027?auto=format&fit=crop&w=1600&q=80';
        $input = 'w-full px-4 py-3 rounded-xl border border-pslb-border bg-pslb-surface text-pslb-ink placeholder-gray-400 text-sm focus:outline-none focus:border-pslb-blue-800 focus:ring-2 focus:ring-pslb-blue-800/20 transition-all';
        $field = fn($name) => $input . ($errors->has($name) ? ' !border-red-400' : '');

        $sujets = [
            'location' => 'Location de voiture',
            'livraison' => 'Livraison & courses',
            'media' => 'Couverture médiatique',
            'autre' => 'Autre demande',
        ];
        $sujetActif = old('sujet_type', request('sujet', 'location'));
        if (!array_key_exists($sujetActif, $sujets)) {
            $sujetActif = 'location';
        }

        $coords = [
            [
                'label' => 'Adresse',
                'lines' => [LOCALISATION_PSLB],
                'action' => null,
                'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/>',
            ],
            [
                'label' => 'Téléphone',
                'lines' => [CONTACT_PSLB, 'Appel direct'],
                'action' => CONTACT_PSLB_LINK,
                'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z"/>',
            ],
            [
                'label' => 'WhatsApp',
                'lines' => [CONTACT_PSLB, 'Réponse rapide'],
                'action' => CONTACT_PSLB_WHATSAPP,
                'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M8.625 12a.375.375 0 11-.75 0 .375.375 0 01.75 0zm4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zM12 21a9 9 0 10-7.5-4L3 21l4.2-1.4A8.96 8.96 0 0012 21z"/>',
            ],
            [
                'label' => 'Email',
                'lines' => [EMAIL_PSLB, 'Réponse sous 24 h ouvrées'],
                'action' => 'mailto:' . EMAIL_PSLB,
                'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0l-9.75 6.75L2.25 6.75"/>',
            ],
        ];

        $faqs = [
            ['Sous quel délai me répondez-vous ?', 'Nous revenons vers vous rapidement, en général dans la journée. Pour une urgence, appelez-nous ou écrivez-nous sur WhatsApp.'],
            ['Comment obtenir un devis ?', 'Choisissez le service concerné dans le formulaire et décrivez votre besoin : vous recevez une proposition claire avant tout engagement.'],
            ['Puis-je réserver un véhicule depuis cette page ?', 'Oui, mais la page Location de voiture vous permet de choisir directement un véhicule et vos dates.'],
            ['Livrez-vous en dehors de Cotonou ?', 'Cela dépend de la destination. Précisez le trajet dans votre message et nous vous indiquons ce qui est possible.'],
        ];
    @endphp

    {{-- ==================== HERO ==================== --}}
    <section class="relative pt-32 pb-24 md:pt-44 md:pb-32 overflow-hidden bg-cover bg-center"
        style="background-image:url('{{ $heroImg }}')">
        <div class="absolute inset-0 bg-gradient-to-br from-pslb-blue-900/90 to-pslb-blue-800/75"></div>
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="max-w-4xl mx-auto text-center">
                <div data-aos="fade-down"
                    class="inline-flex items-center gap-2 bg-white/10 backdrop-blur-sm rounded-full px-4 py-1.5 mb-6">
                    <span class="w-2 h-2 bg-pslb-cyan-500 rounded-full animate-pulse"></span>
                    <span class="text-white text-sm font-medium uppercase tracking-widest">Contact</span>
                </div>
                <h1 data-aos="fade-up" data-aos-delay="100"
                    class="text-4xl md:text-5xl lg:text-6xl font-extrabold text-white leading-tight">
                    Un besoin ?<br><span class="text-pslb-cyan-500">Parlons-en</span>
                </h1>
                <p data-aos="fade-up" data-aos-delay="200" class="text-lg md:text-xl text-gray-200 mt-6 max-w-2xl mx-auto">
                    Location, livraison ou couverture médiatique : écrivez-nous, appelez-nous ou passez par WhatsApp.
                    Nous vous répondons rapidement.
                </p>
                <div data-aos="fade-up" data-aos-delay="300" class="flex flex-col sm:flex-row justify-center gap-4 mt-10">
                    <a href="#formulaire"
                        class="bg-pslb-cyan-500 hover:bg-white hover:text-pslb-blue-800 text-white px-8 py-3 rounded-full text-lg font-semibold shadow-xl inline-flex items-center justify-center gap-2 group transition-colors">
                        Envoyer un message
                        <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 7l5 5m0 0l-5 5m5-5H6" />
                        </svg>
                    </a>
                    <a href="{{ CONTACT_PSLB_LINK }}"
                        class="border border-white/40 hover:bg-white/10 text-white px-8 py-3 rounded-full text-lg font-semibold inline-flex items-center justify-center transition-colors">
                        Appeler directement
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

    {{-- ==================== COORDONNÉES ==================== --}}
    <section class="py-16 bg-white overflow-hidden">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach ($coords as $i => $c)
                    <div class="group bg-pslb-surface rounded-2xl p-6 border-l-4 border-pslb-cyan-500 shadow-sm card-hover"
                        data-aos="fade-up" data-aos-delay="{{ $i * 80 }}">
                        <div
                            class="w-12 h-12 bg-pslb-blue-800/10 group-hover:bg-pslb-cyan-500 rounded-xl flex items-center justify-center mb-5 transition-colors duration-300">
                            <svg class="w-6 h-6 text-pslb-cyan-500 group-hover:text-white transition-colors duration-300"
                                fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">{!! $c['icon'] !!}</svg>
                        </div>
                        <p class="text-xs font-bold text-pslb-cyan-500 uppercase tracking-widest mb-2">{{ $c['label'] }}</p>
                        @foreach ($c['lines'] as $line)
                            <p class="text-sm text-pslb-ink font-medium leading-snug">{{ $line }}</p>
                        @endforeach
                        @if ($c['action'])
                            <a href="{{ $c['action'] }}" @if (str_starts_with($c['action'], 'http')) target="_blank" rel="noopener" @endif
                                class="inline-flex items-center gap-1 text-pslb-blue-800 hover:text-pslb-cyan-500 text-xs font-semibold mt-4 transition-colors">
                                Contacter →
                            </a>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ==================== FORMULAIRE + INFOS ==================== --}}
    <turbo-frame id="contact-pslb">
        <section id="formulaire" class="py-20 bg-pslb-surface overflow-hidden scroll-mt-24">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid lg:grid-cols-5 gap-12 items-start">

                    {{-- Formulaire --}}
                    <div class="lg:col-span-3" data-aos="fade-right">
                        <span class="text-pslb-cyan-500 font-semibold tracking-wider uppercase text-sm">Formulaire</span>
                        <h2 class="text-3xl md:text-4xl font-bold text-pslb-blue-800 mt-2">
                            Envoyez-nous <span class="text-pslb-cyan-500">un message</span>
                        </h2>
                        <div class="w-24 h-1 bg-pslb-cyan-500 mt-5 rounded-full mb-8"></div>

                        {{-- Onglets sujet --}}
                        <div class="flex flex-wrap gap-2 mb-8">
                            @foreach ($sujets as $id => $label)
                                <button type="button" data-sujet="{{ $id }}"
                                    class="sujet-btn px-5 py-2 rounded-full text-sm font-semibold border transition-colors
                                    {{ $id === $sujetActif ? 'bg-pslb-blue-800 text-white border-pslb-blue-800' : 'bg-white text-pslb-blue-800 border-pslb-border hover:border-pslb-cyan-500' }}">
                                    {{ $label }}
                                </button>
                            @endforeach
                        </div>

                        <div class="bg-white rounded-2xl shadow-md p-6 md:p-10">
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
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <form method="POST" action="{{ route('contact.store') }}" data-turbo-frame="contact-pslb"
                                class="space-y-5">
                                @csrf
                                <input type="text" name="website" value="" style="display:none" tabindex="-1" autocomplete="off">
                                <input type="hidden" name="sujet_type" id="sujet_type" value="{{ $sujetActif }}">

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

                                <div>
                                    <label class="block text-sm font-medium text-pslb-ink mb-1.5">Votre message <span class="text-red-500">*</span></label>
                                    <textarea name="message" rows="5" required placeholder="Décrivez votre besoin : dates, trajet, événement, nombre de personnes…"
                                        class="{{ $field('message') }} resize-none">{{ old('message') }}</textarea>
                                </div>

                                @php
                                    $a = session('contact_captcha_a', rand(1, 9));
                                    $b = session('contact_captcha_b', rand(1, 9));
                                    session(['contact_captcha_a' => $a, 'contact_captcha_b' => $b]);
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
                                    Envoyer le message
                                    <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none"
                                        stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>

                    {{-- Colonne droite : horaires + accès rapide + FAQ --}}
                    <div class="lg:col-span-2 space-y-8" data-aos="fade-left">

                        <div class="bg-gradient-to-br from-pslb-blue-800 to-pslb-blue-700 rounded-2xl p-6 relative overflow-hidden">
                            <div class="absolute -top-8 -right-8 w-32 h-32 bg-white/5 rounded-full"></div>
                            <div class="relative z-10">
                                <div class="flex items-center gap-2 mb-1">
                                    <div class="w-1.5 h-4 bg-pslb-cyan-500 rounded-full"></div>
                                    <h3 class="text-white font-bold text-base">Joignable 7j/7</h3>
                                </div>
                                <p class="text-white/60 text-xs mb-5 pl-3.5">Choisissez le canal qui vous arrange.</p>
                                <div class="flex flex-col gap-3">
                                    <a href="{{ CONTACT_PSLB_WHATSAPP }}" target="_blank" rel="noopener"
                                        class="bg-pslb-cyan-500 hover:bg-white hover:text-pslb-blue-800 text-white text-center px-6 py-3 rounded-full text-sm font-semibold transition-colors">Écrire sur WhatsApp</a>
                                    <a href="{{ CONTACT_PSLB_LINK }}"
                                        class="border border-white/40 hover:bg-white/10 text-white text-center px-6 py-3 rounded-full text-sm font-semibold transition-colors">Appeler PSLB</a>
                                </div>
                            </div>
                        </div>

                        <div>
                            <h3 class="text-lg font-bold text-pslb-blue-800 mb-4">Questions fréquentes</h3>
                            <div class="space-y-3">
                                @foreach ($faqs as [$q, $r])
                                    <div class="faq-item bg-white rounded-xl border border-pslb-border overflow-hidden">
                                        <button type="button"
                                            class="faq-trigger w-full flex items-center justify-between gap-4 px-5 py-4 text-left">
                                            <span class="text-sm font-semibold text-pslb-blue-800">{{ $q }}</span>
                                            <svg class="faq-chevron w-4 h-4 text-pslb-cyan-500 shrink-0 transition-transform duration-200"
                                                fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                            </svg>
                                        </button>
                                        <div class="faq-body hidden px-5 pb-4">
                                            <p class="text-sm text-pslb-muted leading-relaxed">{{ $r }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
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
                    Pressé ? <span class="text-pslb-cyan-500">Réservez directement</span>
                </h2>
                <p class="text-white/70 mb-8 max-w-xl mx-auto">
                    Choisissez votre véhicule et vos dates sur la page de location : nous confirmons ensuite la disponibilité et le tarif.
                </p>
                <a href="{{ Route::has('location') ? route('location') : '#' }}"
                    class="inline-flex bg-pslb-cyan-500 hover:bg-white hover:text-pslb-blue-800 text-white px-8 py-3 rounded-full text-lg font-semibold shadow-xl transition-colors">Voir nos véhicules</a>
            </div>
        </div>
    </section>

    <script>
        (() => {
            const activeCls = ['bg-pslb-blue-800', 'text-white', 'border-pslb-blue-800'];
            const idleCls = ['bg-white', 'text-pslb-blue-800', 'border-pslb-border', 'hover:border-pslb-cyan-500'];

            const syncSujet = () => {
                const hidden = document.getElementById('sujet_type');
                if (!hidden) return;
                document.querySelectorAll('.sujet-btn').forEach(b => {
                    const on = b.dataset.sujet === hidden.value;
                    activeCls.forEach(c => b.classList.toggle(c, on));
                    idleCls.forEach(c => b.classList.toggle(c, !on));
                });
            };

            // Listeners posés une seule fois (évite les doublons avec Turbo)
            if (!window.__pslbContactBound) {
                window.__pslbContactBound = true;

                document.addEventListener('click', (e) => {
                    const btn = e.target.closest('.sujet-btn');
                    if (btn) {
                        document.getElementById('sujet_type').value = btn.dataset.sujet;
                        syncSujet();
                        return;
                    }

                    const trigger = e.target.closest('.faq-trigger');
                    if (trigger) {
                        const item = trigger.closest('.faq-item');
                        const body = item.querySelector('.faq-body');
                        const wasOpen = !body.classList.contains('hidden');
                        document.querySelectorAll('.faq-body').forEach(b => b.classList.add('hidden'));
                        document.querySelectorAll('.faq-chevron').forEach(c => c.classList.remove('rotate-180'));
                        if (!wasOpen) {
                            body.classList.remove('hidden');
                            item.querySelector('.faq-chevron').classList.add('rotate-180');
                        }
                    }
                });

                document.addEventListener('turbo:frame-load', (e) => {
                    if (e.target.id !== 'contact-pslb') return;
                    syncSujet();
                    e.target.querySelectorAll('button[type="submit"]').forEach(b => b.disabled = false);
                    window.AOS?.refreshHard();
                });
            }

            syncSujet();
        })();
    </script>
@endsection
