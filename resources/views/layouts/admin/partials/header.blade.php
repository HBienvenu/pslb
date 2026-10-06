<header class="h-[60px] shrink-0 flex items-center gap-[14px] px-[16px] md:px-[22px]
           bg-white dark:bg-pslb-blue-900
           border-b border-pslb-border dark:border-white/[.08]">

    <!-- hamburger (mobile only, caché dès que la sidebar est visible) -->
    <x-admin.ux.button.hamburger />

    <!-- Breadcrumb (toujours visible) -->
    <x-admin.ux.breadcrumb :title="$title ?? ''" />

    <!-- Right cluster -->
    <div class="ml-auto flex items-center gap-2 min-w-0">

        <!-- Notification -->
        <button class="relative w-[34px] h-[34px] rounded-lg border border-pslb-border dark:border-white/[.10]
                   bg-pslb-surface dark:bg-pslb-blue-800 text-pslb-muted dark:text-pslb-blue-200
                   flex items-center justify-center cursor-pointer shrink-0
                   transition-colors hover:text-pslb-blue-800 dark:hover:text-white">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path
                    d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
            </svg>
            <span class="absolute top-1.5 right-1.5 w-1.5 h-1.5 rounded-full bg-pslb-cyan-500
                       border-[1.5px] border-white dark:border-pslb-blue-900"></span>
        </button>

        <!-- Dark mode toggle -->
        <button id="theme-btn" onclick="toggleTheme()" class="w-[34px] h-[34px] rounded-lg border border-pslb-border dark:border-white/[.10]
                   bg-pslb-surface dark:bg-pslb-blue-800 text-pslb-muted dark:text-pslb-blue-200
                   flex items-center justify-center cursor-pointer shrink-0
                   transition-colors hover:text-pslb-blue-800 dark:hover:text-white" title="Mode sombre / clair">
            <svg id="ic-sun" class="w-4 h-4 hidden" fill="none" stroke="currentColor" stroke-width="2"
                viewBox="0 0 24 24">
                <circle cx="12" cy="12" r="5" />
                <path
                    d="M12 1v2M12 21v2M4.22 4.22l1.42 1.42M18.36 18.36l1.42 1.42M1 12h2M21 12h2M4.22 19.78l1.42-1.42M18.36 5.64l1.42-1.42" />
            </svg>
            <svg id="ic-moon" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z" />
            </svg>
        </button>

        <!-- Profile button + dropdown -->
        <div class="relative shrink-0" id="profile-wrap">
            <button onclick="toggleProfile(event)" class="flex items-center gap-[8px] pl-[7px] pr-[10px] h-[34px] rounded-lg
                       border border-pslb-border dark:border-white/[.10]
                       bg-pslb-surface dark:bg-pslb-blue-800 cursor-pointer
                       transition-colors hover:border-pslb-cyan-500/50">
                <!-- Avatar -->
                <div class="w-[22px] h-[22px] rounded-full bg-gradient-to-br
                           from-pslb-cyan-500 to-pslb-blue-800
                           flex items-center justify-center
                           text-white font-display text-[9px] font-bold shrink-0">
                    AD
                </div>
                <!-- Nom : caché entre md et lg pour ne pas écraser -->
                <span class="hidden lg:inline text-[12px] font-medium
                           text-pslb-ink dark:text-pslb-blue-100">
                    {{ auth()->user()->name }}
                </span>
                <!-- Chevron -->
                <svg class="w-[11px] h-[11px] text-pslb-muted dark:text-pslb-blue-300 shrink-0" fill="none"
                    stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path d="M19 9l-7 7-7-7" />
                </svg>
            </button>

            <!-- Dropdown -->
            <div id="profile-dropdown">
                <div class="px-3 py-2 mb-1">
                    <p class="text-[12.5px] font-semibold text-pslb-ink dark:text-pslb-blue-50 m-0">
                        Bienvenu
                    </p>
                    <p class="text-[11px] text-pslb-muted dark:text-pslb-blue-300 m-0">
                        bienvenuhounye05@gmail.com
                    </p>
                </div>
                <div class="pd-divider"></div>
                <a href="" class="pd-item">
                    <svg class="w-[14px] h-[14px] shrink-0 text-pslb-muted" fill="none" stroke="currentColor"
                        stroke-width="2" viewBox="0 0 24 24">
                        <path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    Modifier le profil
                </a>

                <a href="" class="pd-item">
                    <svg class="w-[14px] h-[14px] shrink-0 text-pslb-muted" fill="none" stroke="currentColor"
                        stroke-width="2" viewBox="0 0 24 24">
                        <path
                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                    Changer le mot de passe
                </a>

                <div class="pd-divider"></div>
                <form method="POST" action="{{ route('logout') }}" data-turbo="false">
                    @csrf
                    <button type="submit" class="pd-item danger flex items-center gap-2">
                        <svg class="w-[14px] h-[14px] shrink-0" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24">
                            <path
                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        Déconnexion
                    </button>
                </form>
            </div>
        </div>
    </div>
</header>
