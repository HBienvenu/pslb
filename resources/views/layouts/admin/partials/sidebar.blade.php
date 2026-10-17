<!-- ══ SIDEBAR ══ -->
<aside id="sidebar"
    class="w-[252px] min-w-[252px] flex flex-col h-full bg-white dark:bg-pslb-blue-900 border-r border-pslb-border dark:border-white/[.08] overflow-hidden">

    <!-- Sidebar header -->
    <div
        class="h-[60px] flex items-center gap-[10px] px-4 border-b border-pslb-border dark:border-white/[.08] shrink-0">
        <img src="{{ asset('images/pslb-logo.png') }}" alt="PSLB" class="w-[33px] h-[33px] shrink-0 object-contain" />
        <div>
            <p
                class="font-display text-[12.5px] font-bold text-pslb-blue-800 dark:text-pslb-blue-50 tracking-[.02em] leading-[1.15] m-0">
                PSLB</p>
            <p class="text-[8.5px] text-pslb-muted dark:text-pslb-blue-300 font-medium tracking-[.07em] m-0">
                POSITIF SERA LE BILAN</p>
        </div>
        <x-admin.ux.button.close-sidebar onClick="closeSidebar()" />
    </div>

    <!-- Nav -->
    <nav class="flex-1 overflow-y-auto py-3 px-2">
        <!-- ── PRINCIPAL ── -->
        <x-admin.sidebar.section label="Principal" color="bg-pslb-cyan-500" />

        <ul class="list-none p-0 m-0 mb-4 space-y-0.5">
            <li>
                <a href="{{ route('dashboard') }}" class="sl {{ request()->routeIs('dashboard') ? 'on' : '' }}">
                    <svg class="w-[15px] h-[15px] shrink-0" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <rect x="3" y="3" width="7" height="7" rx="1" />
                        <rect x="14" y="3" width="7" height="7" rx="1" />
                        <rect x="3" y="14" width="7" height="7" rx="1" />
                        <rect x="14" y="14" width="7" height="7" rx="1" />
                    </svg>
                    Tableau de bord
                </a>
            </li>
        </ul>

        <!-- ── LOCATION DE VÉHICULES ── -->
        <x-admin.sidebar.section label="Véhicules" color="bg-pslb-cyan-500" />

        @php
            $ddVehicules = request()->routeIs(['admin.vehicules.*', 'admin.categories-vehicule.*']);
            $ddReservations = request()->routeIs('admin.reservations.*');
        @endphp

        <ul class="list-none p-0 m-0 mb-4 space-y-0.5">
            {{-- Véhicules --}}
            <li class="dd-wrap {{ $ddVehicules ? 'open' : '' }}" id="ddVehicules">
                <div class="sl {{ $ddVehicules ? 'on' : '' }}" onclick="toggleDD('ddVehicules')">
                    <svg class="w-[15px] h-[15px] shrink-0" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <path
                            d="M8 17h8M8 17a2 2 0 11-4 0 2 2 0 014 0zm12 0a2 2 0 11-4 0 2 2 0 014 0zM3 13l2-6a2 2 0 011.9-1.4h10.2A2 2 0 0119 7l2 6M3 13v4h1m17-4v4h-1M3 13h18" />
                    </svg>
                    <span>Véhicules</span>
                    <svg class="dd-arr w-[13px] h-[13px]" fill="none" stroke="currentColor" stroke-width="2.5"
                        viewBox="0 0 24 24">
                        <path d="M9 5l7 7-7 7" />
                    </svg>
                </div>
                <div class="dd-body pt-0.5">
                    <a href="{{ route('admin.vehicules.index') }}"
                        class="dc {{ request()->routeIs('admin.vehicules.*') ? 'on' : '' }}">
                        <svg class="w-3 h-3 shrink-0" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24">
                            <path d="M4 6h16M4 10h16M4 14h10" />
                        </svg>
                        Tous les véhicules
                    </a>
                    <a href="{{ route('admin.categories-vehicule.index') }}"
                        class="dc {{ request()->routeIs('admin.categories-vehicule.*') ? 'on' : '' }}">
                        <svg class="w-3 h-3 shrink-0" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24">
                            <path
                                d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l5 5a2 2 0 01.586 1.414V19a2 2 0 01-2 2H7a2 2 0 01-2-2V5a2 2 0 012-2z" />
                        </svg>
                        Catégories
                    </a>
                </div>
            </li>

            {{-- Réservations --}}
            <li>
                <a href="{{ route('admin.reservations.index') }}" class="sl {{ $ddReservations ? 'on' : '' }}">
                    <svg class="w-[15px] h-[15px] shrink-0" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <path
                            d="M8 7V3m8 4V3M3 11h18M5 5h14a2 2 0 012 2v12a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2z" />
                    </svg>
                    Réservations
                    <span {{ reservationsEnAttente() == 0 ? 'hidden' : '' }}
                        class="ml-auto inline-block px-2 py-0.5 rounded text-[11px] font-medium bg-amber-500/10 text-amber-700 dark:text-amber-300">
                        {{ reservationsEnAttente() }}
                    </span>
                </a>
            </li>
        </ul>

        <!-- ── COUVERTURE MÉDIATIQUE ── -->
        <x-admin.sidebar.section label="Couverture médiatique" color="bg-pslb-cyan-500" />

        <ul class="list-none p-0 m-0 mb-4 space-y-0.5">
            <li>
                <a href="{{ route('admin.demandes-couverture.index') }}"
                    class="sl {{ request()->routeIs('admin.demandes-couverture.*') ? 'on' : '' }}">
                    <svg class="w-[15px] h-[15px] shrink-0" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <path
                            d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9zM15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    Demandes de couverture
                    <span {{ demandesCouvertureEnAttente() == 0 ? 'hidden' : '' }}
                        class="ml-auto inline-block px-2 py-0.5 rounded text-[11px] font-medium bg-emerald-500/10 text-emerald-700 dark:text-emerald-400">
                        {{ demandesCouvertureEnAttente() }}
                    </span>
                </a>
            </li>
        </ul>

        <!-- ── LIVRAISONS ── -->
        <x-admin.sidebar.section label="Livraisons" color="bg-pslb-cyan-500" />

        <ul class="list-none p-0 m-0 mb-4 space-y-0.5">
            <li>
                <a href="{{ route('admin.livraisons.index') }}"
                    class="sl {{ request()->routeIs('admin.livraisons.*') ? 'on' : '' }}">
                    <svg class="w-[15px] h-[15px] shrink-0" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <path d="M3 8h12v9H3V8zM15 10h3l3 3v4h-6v-7zM7 18a2 2 0 100-4 2 2 0 000 4zm10 0a2 2 0 100-4 2 2 0 000 4z" />
                    </svg>
                    Soumissions de livraison
                    <span {{ livraisonsEnAttente() == 0 ? 'hidden' : '' }}
                        class="ml-auto inline-block px-2 py-0.5 rounded text-[11px] font-medium bg-cyan-500/10 text-cyan-700 dark:text-cyan-300">
                        {{ livraisonsEnAttente() }}
                    </span>
                </a>
            </li>
        </ul>

        <!-- ── CONTACT ── -->
        <x-admin.sidebar.section label="Contact" color="bg-pslb-cyan-500" />

        <ul class="list-none p-0 m-0 mb-4 space-y-0.5">
            <li>
                <a href="{{ route('admin.contacts.index') }}"
                    class="sl {{ request()->routeIs('admin.contacts.*') ? 'on' : '' }}">
                    <svg class="w-[15px] h-[15px] shrink-0" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <path d="M7 8h10a2 2 0 012 2v7a2 2 0 01-2 2H7l-4 3v-3H7a2 2 0 01-2-2v-7a2 2 0 012-2z" />
                        <path d="M8 11h8M8 15h5" />
                    </svg>
                    Messages reçus
                    <span {{ messagesContactNonLus() == 0 ? 'hidden' : '' }}
                        class="ml-auto inline-block px-2 py-0.5 rounded text-[11px] font-medium bg-red-500/10 text-red-700 dark:text-red-300">
                        {{ messagesContactNonLus() }}
                    </span>
                </a>
            </li>
        </ul>

        <!-- ── FORMATION & DIGITAL ── -->
        <x-admin.sidebar.section label="Formation / Digital" color="bg-pslb-cyan-500" />

        <ul class="list-none p-0 m-0 mb-4 space-y-0.5">
            <li>
                <a href="{{ route('admin.demandes-formation.index') }}"
                    class="sl {{ request()->routeIs('admin.demandes-formation.*') ? 'on' : '' }}">
                    <svg class="w-[15px] h-[15px] shrink-0" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <path d="M4 7.5A2.5 2.5 0 016.5 5h11A2.5 2.5 0 0120 7.5v9A2.5 2.5 0 0117.5 19h-11A2.5 2.5 0 014 16.5v-9zM8 9h8M8 12h8M8 15h5" />
                    </svg>
                    Demandes digital
                    <span {{ demandesFormationEnAttente() == 0 ? 'hidden' : '' }}
                        class="ml-auto inline-block px-2 py-0.5 rounded text-[11px] font-medium bg-violet-500/10 text-violet-700 dark:text-violet-300">
                        {{ demandesFormationEnAttente() }}
                    </span>
                </a>
            </li>
        </ul>

        <!-- ── Profil ── -->
        <x-admin.sidebar.section label="Profil" color="bg-cyan-500 dark:bg-cyan-600" />
        <ul class="list-none p-0 m-0 space-y-0.5">
            <li>
                <a href="{{ route('admin.profil.index') }}" class="sl {{ request()->routeIs('admin.profil.*') ? 'on' : '' }}">
                    <svg class="w-[15px] h-[15px] shrink-0" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <path d="M12 11a4 4 0 100-8 4 4 0 000 8z" />
                        <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2" />
                        <path d="M17 9l2 2 4-4" />
                    </svg>
                    Gestion Profil
                </a>
            </li>
        </ul>

    </nav>

    <!-- Sidebar footer -->
    <x-admin.sidebar.footer />
</aside>
