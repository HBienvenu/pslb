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
                </a>
            </li>
        </ul>

        <!-- ── ADMINISTRATION ── -->
        <x-admin.sidebar.section label="Administration" color="bg-gray-400 dark:bg-gray-600" />

        <ul class="list-none p-0 m-0 space-y-0.5 mb-4">
            <li>
                <a href="" class="sl">
                    <svg class="w-[15px] h-[15px] shrink-0" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <path d="M12 11a4 4 0 100-8 4 4 0 000 8z" />
                        <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2" />
                        <path d="M17 9l2 2 4-4" />
                    </svg>
                    Role
                </a>
            </li>
            <li>
                <a href="" class="sl">
                    <svg class="w-[15px] h-[15px] shrink-0" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <path
                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    Utilisateurs système
                </a>
            </li>
        </ul>

        <!-- ── Profil ── -->
        <x-admin.sidebar.section label="Profil" color="bg-cyan-500 dark:bg-cyan-600" />
        <ul class="list-none p-0 m-0 space-y-0.5">
            <li>
                <a href="" class="sl">
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
