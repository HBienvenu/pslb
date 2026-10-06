@extends('layouts.site.app', ['title' => 'Connexion'])

@section('meta')
    <meta name="robots" content="noindex, nofollow">
@endsection

@section('content')
    <main class="pt-20 min-h-screen grid lg:grid-cols-2 font-pslb bg-pslb-surface text-pslb-ink antialiased">

        {{-- Panneau de marque (desktop) --}}
        <aside class="hidden lg:flex relative flex-col justify-between p-12 text-white overflow-hidden
                                  bg-gradient-to-br from-pslb-blue-900 to-pslb-blue-700">
            <div class="absolute -right-32 -top-32 w-[460px] h-[460px] rounded-full border border-pslb-cyan-500/25">
            </div>
            <div class="absolute -left-24 -bottom-40 w-[380px] h-[380px] rounded-full bg-pslb-cyan-500/10 blur-3xl">
            </div>

            <a href="{{ route('accueil') }}" class="relative">
                <img src="{{ asset('images/pslb-logo.png') }}" alt="PSLB" class="h-12 w-auto brightness-0 invert">
            </a>

            <div class="relative max-w-md">
                <span class="text-pslb-cyan-500 font-semibold uppercase tracking-widest text-xs">Espace
                    administration</span>
                <h1 class="text-4xl font-extrabold leading-tight mt-3">
                    Pilotez <span class="text-pslb-cyan-500">votre activité</span>
                </h1>
                <p class="text-white/70 mt-4 leading-relaxed">
                    Réservations de véhicules, livraisons et demandes de couverture médiatique : tout au même endroit.
                </p>
            </div>

            <p class="relative text-xs text-white/40">© {{ date('Y') }} PSLB Entreprise — Accès réservé.</p>
        </aside>

        {{-- Formulaire --}}
        <section class="flex items-center justify-center px-4 py-12 sm:px-8">
            <div class="w-full max-w-md">

                <a href="{{ route('accueil') }}" class="lg:hidden block mb-8">
                    <img src="{{ asset('images/pslb-logo.png') }}" alt="PSLB" class="h-12 w-auto">
                </a>

                <span class="text-pslb-cyan-500 font-semibold tracking-wider uppercase text-sm">Administration</span>
                <h2 class="text-3xl font-bold text-pslb-blue-800 mt-1">Connexion</h2>
                <div class="w-16 h-1 bg-pslb-cyan-500 rounded-full mt-3 mb-8"></div>

                @if ($message = session('message') ?? session('erreur-connexion'))
                    <div class="relative mb-4 text-red-700">
                        {{ $message }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login.store') }}" data-turbo="false"
                    class="bg-white rounded-2xl shadow-md border border-pslb-border p-6 sm:p-8 space-y-5">
                    @csrf

                    @if ($errors->any())
                        <div role="alert" class="bg-red-50 border border-red-200 text-red-600 rounded-xl px-4 py-3 text-sm">
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <div>
                        <label for="email" class="block text-sm font-medium text-pslb-ink mb-1.5">Adresse email</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                            autocomplete="username" placeholder="admin@pslb.com" class="w-full px-4 py-3 rounded-xl border bg-pslb-surface text-sm placeholder-gray-400
                                               focus:outline-none focus:border-pslb-blue-800 focus:ring-2 focus:ring-pslb-blue-800/20 transition-all
                                               {{ $errors->has('email') ? 'border-red-400' : 'border-pslb-border' }}">
                    </div>

                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label for="password" class="block text-sm font-medium text-pslb-ink">Mot de passe</label>
                            <a href="{{ route('password.forgot') }}"
                                class="text-xs font-semibold text-pslb-blue-800 hover:text-pslb-cyan-500 transition-colors">
                                Mot de passe oublié ?
                            </a>
                        </div>
                        <div class="relative">
                            <input id="password" type="password" name="password" required autocomplete="current-password"
                                placeholder="••••••••"
                                class="w-full pl-4 pr-24 py-3 rounded-xl border border-pslb-border bg-pslb-surface text-sm placeholder-gray-400
                                                   focus:outline-none focus:border-pslb-blue-800 focus:ring-2 focus:ring-pslb-blue-800/20 transition-all">
                            <button type="button" id="togglePw" aria-label="Afficher le mot de passe"
                                class="absolute inset-y-0 right-3 text-xs font-semibold text-pslb-blue-800 hover:text-pslb-cyan-500 transition-colors">
                                Afficher
                            </button>
                        </div>
                    </div>

                    <label class="flex items-center gap-2.5 text-sm text-pslb-muted cursor-pointer select-none">
                        <input type="checkbox" name="remember" value="1" @checked(old('remember'))
                            class="w-4 h-4 rounded border-pslb-border text-pslb-blue-800 focus:ring-pslb-cyan-500">
                        Rester connecté sur cet appareil
                    </label>

                    <button type="submit"
                        class="w-full bg-pslb-blue-800 hover:bg-pslb-cyan-500 text-white py-3.5 rounded-xl font-semibold text-sm
                                           transition-all duration-300 hover:shadow-lg hover:-translate-y-0.5 flex items-center justify-center gap-2 group">
                        Se connecter
                        <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none"
                            stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                        </svg>
                    </button>
                </form>

                <p class="text-center text-sm mt-6">
                    <a href="{{ route('accueil') }}" class="text-pslb-muted hover:text-pslb-cyan-500 transition-colors">←
                        Retour au site</a>
                </p>
            </div>
        </section>
    </main>

    <script>
        (() => {
            const pw = document.getElementById('password'), btn = document.getElementById('togglePw');
            btn.addEventListener('click', () => {
                const show = pw.type === 'password';
                pw.type = show ? 'text' : 'password';
                btn.textContent = show ? 'Masquer' : 'Afficher';
                btn.setAttribute('aria-label', show ? 'Masquer le mot de passe' : 'Afficher le mot de passe');
            });
        })();
    </script>
@endsection
