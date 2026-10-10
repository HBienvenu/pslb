@extends('layouts.site.app', ['title' => 'Livraison & courses'])

@section('content')
    @php
        $heroImg = 'https://images.unsplash.com/photo-1586528116311-ad8dd3c8310d?auto=format&fit=crop&w=1600&q=80';
        $input = 'w-full px-4 py-3 rounded-xl border border-pslb-border bg-pslb-surface text-pslb-ink placeholder-gray-400 text-sm focus:outline-none focus:border-pslb-blue-800 focus:ring-2 focus:ring-pslb-blue-800/20 transition-all';
        $field = fn($name) => $input . ($errors->has($name) ? ' !border-red-400' : '');
        $check = '<path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75"/>';

        $typesColis = [
            'document' => 'Document / courrier',
            'petit' => 'Petit colis',
            'moyen' => 'Colis moyen',
            'gros' => 'Gros colis / volumineux',
            'fragile' => 'Colis fragile',
        ];

        $steps = [
            ['Dites-nous votre besoin', 'Renseignez la ville, l’adresse, le type de colis et la date souhaitée.', 'bg-pslb-cyan-500'],
            ['Nous validons', 'Notre équipe vérifie les détails et vous recontacte rapidement.', 'bg-pslb-blue-800'],
            ['On collecte le colis', 'Le colis est pris en charge selon votre demande et votre localisation.', 'bg-pslb-blue-800'],
            ['Livraison effectuée', 'Le colis est livré à destination et le suivi est clôturé.', 'bg-pslb-cyan-500'],
        ];

        $avantages = [
            ['title' => 'Suivi clair', 'text' => 'Chaque demande est suivie dans le back office avant la validation et la livraison.'],
            ['title' => 'Planification simple', 'text' => 'Définissez la ville de départ, la ville d’arrivée et le moment idéal pour la livraison.'],
            ['title' => 'Colis personnalisés', 'text' => 'Que ce soit un courrier, un paquet ou un article délicat, nous adaptons la prise en charge.'],
        ];
    @endphp

    <section class="relative pt-32 pb-24 md:pt-44 md:pb-32 overflow-hidden bg-cover bg-center"
        style="background-image:url('{{ $heroImg }}')">
        <div class="absolute inset-0 bg-gradient-to-br from-pslb-blue-900/90 to-pslb-blue-800/75"></div>
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="max-w-4xl mx-auto">
                <div data-aos="fade-down"
                    class="inline-flex items-center gap-2 bg-white/10 backdrop-blur-sm rounded-full px-4 py-1.5 mb-6">
                    <span class="w-2 h-2 bg-pslb-cyan-500 rounded-full animate-pulse"></span>
                    <span class="text-white text-sm font-medium uppercase tracking-widest">Livraison & courses</span>
                </div>
                <h1 data-aos="fade-up" data-aos-delay="100"
                    class="text-4xl md:text-5xl lg:text-6xl font-extrabold text-white leading-tight">
                    Faites livrer votre colis<br><span class="text-pslb-cyan-500">rapidement et en toute confiance</span>
                </h1>
                <p data-aos="fade-up" data-aos-delay="200" class="text-lg md:text-xl text-gray-200 mt-6 max-w-2xl">
                    Envoyez-nous votre demande de livraison : ville de départ, destination, type de colis, date souhaitée et plus encore.
                    Nous traitons votre demande dans notre back office avant validation.
                </p>
                <div data-aos="fade-up" data-aos-delay="300" class="flex flex-col sm:flex-row gap-4 mt-10">
                    <a href="#demande"
                        class="bg-pslb-cyan-500 hover:bg-white hover:text-pslb-blue-800 text-white px-8 py-3 rounded-full text-lg font-semibold shadow-xl inline-flex items-center justify-center gap-2 group transition-colors">
                        Déposer une demande
                        <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 7l5 5m0 0l-5 5m5-5H6" />
                        </svg>
                    </a>
                    <a href="#processus"
                        class="border border-white/40 hover:bg-white/10 text-white px-8 py-3 rounded-full text-lg font-semibold inline-flex items-center justify-center transition-colors">
                        Voir le processus
                    </a>
                </div>
            </div>
        </div>
        <div class="absolute bottom-0 left-0 w-full">
            <svg viewBox="0 0 1200 120" preserveAspectRatio="none" class="relative block w-full h-12 md:h-16">
                <path d="M0,0V5.63C149.93,59,314.09,71.32,475.83,42.57c77.44-13.74,149.51-38.5,229.58-45.86C870.13-9.08,1049.16,21.06,1200,39.66V0Z" fill="white" />
            </svg>
        </div>
    </section>

    <section class="py-16 bg-white overflow-hidden">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-3 gap-6">
                @foreach ($avantages as $i => $item)
                    <div class="group bg-pslb-surface rounded-2xl p-6 border-l-4 border-pslb-cyan-500 shadow-sm card-hover" data-aos="fade-up" data-aos-delay="{{ $i * 80 }}">
                        <div class="w-12 h-12 bg-pslb-blue-800/10 rounded-xl flex items-center justify-center mb-5">
                            <svg class="w-6 h-6 text-pslb-cyan-500" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">{!! $check !!}</svg>
                        </div>
                        <h3 class="text-lg font-bold text-pslb-blue-800">{{ $item['title'] }}</h3>
                        <p class="text-pslb-muted text-sm mt-2 leading-relaxed">{{ $item['text'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section id="processus" class="py-20 bg-white overflow-hidden">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-14" data-aos="fade-up">
                <span class="text-pslb-cyan-500 font-semibold tracking-wider uppercase text-sm">Comment ça marche</span>
                <h2 class="text-3xl md:text-4xl font-bold text-pslb-blue-800 mt-2">
                    Un processus <span class="text-pslb-cyan-500">simple et fiable</span>
                </h2>
                <div class="w-24 h-1 bg-pslb-cyan-500 mx-auto mt-5 rounded-full"></div>
            </div>
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

    <turbo-frame id="livraison-pslb">
        <section id="demande" class="py-20 bg-pslb-surface overflow-hidden scroll-mt-24">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid lg:grid-cols-5 gap-12 items-start">
                    <div class="lg:col-span-3" data-aos="fade-right">
                        <span class="text-pslb-cyan-500 font-semibold tracking-wider uppercase text-sm">Formulaire</span>
                        <h2 class="text-3xl md:text-4xl font-bold text-pslb-blue-800 mt-2">
                            Déposez votre demande <span class="text-pslb-cyan-500">de livraison</span>
                        </h2>
                        <div class="w-24 h-1 bg-pslb-cyan-500 mt-5 rounded-full mb-8"></div>
                        <p class="text-pslb-muted leading-relaxed mb-8">
                            Décrivez votre colis, votre trajet et la date souhaitée. Votre demande est ensuite traitée dans le back office avant confirmation.
                        </p>

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

                            <form method="POST" action="{{ route('livraison.store') }}" enctype="multipart/form-data" data-turbo-frame="livraison-pslb" class="space-y-5">
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
                                        <label class="block text-sm font-medium text-pslb-ink mb-1.5">Ville de départ <span class="text-red-500">*</span></label>
                                        <input type="text" name="ville_depart" value="{{ old('ville_depart') }}" required placeholder="Cotonou" class="{{ $field('ville_depart') }}">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-pslb-ink mb-1.5">Ville d’arrivée <span class="text-red-500">*</span></label>
                                        <input type="text" name="ville_arrivee" value="{{ old('ville_arrivee') }}" required placeholder="Abomey" class="{{ $field('ville_arrivee') }}">
                                    </div>
                                </div>

                                <div class="grid sm:grid-cols-2 gap-5">
                                    <div>
                                        <label class="block text-sm font-medium text-pslb-ink mb-1.5">Adresse de départ <span class="text-red-500">*</span></label>
                                        <textarea name="adresse_depart" rows="3" required placeholder="Ex : Rue x, quartier y" class="{{ $field('adresse_depart') }} resize-none">{{ old('adresse_depart') }}</textarea>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-pslb-ink mb-1.5">Adresse de livraison <span class="text-red-500">*</span></label>
                                        <textarea name="adresse_livraison" rows="3" required placeholder="Ex : Rue a, quartier b" class="{{ $field('adresse_livraison') }} resize-none">{{ old('adresse_livraison') }}</textarea>
                                    </div>
                                </div>

                                <div class="grid sm:grid-cols-2 gap-5">
                                    <div>
                                        <label class="block text-sm font-medium text-pslb-ink mb-1.5">Type de colis <span class="text-red-500">*</span></label>
                                        <select name="type_colis" required class="{{ $field('type_colis') }}">
                                            <option value="">Choisir</option>
                                            @foreach ($typesColis as $key => $label)
                                                <option value="{{ $key }}" @selected(old('type_colis') === $key)>{{ $label }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-pslb-ink mb-1.5">Poids estimé (kg)</label>
                                        <input type="number" step="0.01" min="0" name="poids_kg" value="{{ old('poids_kg') }}" placeholder="2.5" class="{{ $field('poids_kg') }}">
                                    </div>
                                </div>

                                <div class="grid sm:grid-cols-2 gap-5">
                                    <div>
                                        <label class="block text-sm font-medium text-pslb-ink mb-1.5">Date de livraison <span class="text-red-500">*</span></label>
                                        <input type="date" name="date_livraison" value="{{ old('date_livraison') }}" required class="{{ $field('date_livraison') }}">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-pslb-ink mb-1.5">Heure souhaitée</label>
                                        <input type="time" name="heure_livraison" value="{{ old('heure_livraison') }}" class="{{ $field('heure_livraison') }}">
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-pslb-ink mb-1.5">Description du colis</label>
                                    <textarea name="description" rows="4" placeholder="Décrivez le colis, l’état, les précautions à prendre…" class="{{ $field('description') }} resize-none">{{ old('description') }}</textarea>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-pslb-ink mb-1.5">Photo du colis <span class="text-red-500">*</span></label>
                                    <input type="file" name="image_colis" accept="image/*" required class="{{ $field('image_colis') }} file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-pslb-blue-800 file:text-white hover:file:bg-pslb-cyan-500">
                                </div>

                                @php
                                    $a = session('livraison_captcha_a', rand(1, 9));
                                    $b = session('livraison_captcha_b', rand(1, 9));
                                    session(['livraison_captcha_a' => $a, 'livraison_captcha_b' => $b]);
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
                                    Envoyer la demande
                                    <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none"
                                        stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>

                    <div class="lg:col-span-2 space-y-8" data-aos="fade-left">
                        <div class="bg-gradient-to-br from-pslb-blue-800 to-pslb-blue-700 rounded-2xl p-6 relative overflow-hidden">
                            <div class="absolute -top-8 -right-8 w-32 h-32 bg-white/5 rounded-full"></div>
                            <div class="relative z-10">
                                <div class="flex items-center gap-2 mb-1">
                                    <div class="w-1.5 h-4 bg-pslb-cyan-500 rounded-full"></div>
                                    <h3 class="text-white font-bold text-base">Contact rapide</h3>
                                </div>
                                <p class="text-white/60 text-xs mb-5 pl-3.5">Choisissez le canal qui vous arrange.</p>
                                <div class="flex flex-col gap-3">
                                    <a href="{{ CONTACT_PSLB_WHATSAPP }}" target="_blank" rel="noopener"
                                        class="bg-pslb-cyan-500 hover:bg-white hover:text-pslb-blue-800 text-white text-center px-6 py-3 rounded-full text-sm font-semibold transition-colors">Écrire sur WhatsApp</a>
                                    <a href="{{ CONTACT_PSLB_LINK }}"
                                        class="border border-white/30 text-white text-center px-6 py-3 rounded-full text-sm font-semibold hover:bg-white/10 transition-colors">Appeler directement</a>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white rounded-2xl p-6 shadow-sm border border-pslb-border">
                            <h3 class="text-lg font-bold text-pslb-blue-800 mb-4">Informations utiles</h3>
                            <ul class="space-y-3 text-sm text-pslb-ink">
                                @foreach (['Prix selon la distance et le poids du colis.', 'La demande est d’abord confirmée par le back office.', 'Les colis sensibles peuvent être signalés dans la description.'] as $item)
                                    <li class="flex items-start gap-2.5">
                                        <svg class="w-4 h-4 text-pslb-cyan-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">{!! $check !!}</svg>
                                        <span>{{ $item }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </turbo-frame>
@endsection
