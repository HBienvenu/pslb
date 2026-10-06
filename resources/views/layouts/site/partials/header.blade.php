@php
    $navLinks = [
        ['label' => 'Accueil', 'route' => 'accueil'],
        ['label' => 'Location de voiture', 'route' => 'location'],
        ['label' => 'Livraison & courses', 'route' => 'livraison'],
        ['label' => 'Couverture médiatique', 'route' => 'media'],
        ['label' => 'À propos', 'route' => 'apropos'],
    ];
    $isActive = fn(string $name) => request()->routeIs($name, $name . '.*');
@endphp

<header id="siteHeader"
    class="fixed top-0 inset-x-0 z-50 bg-white/95 backdrop-blur border-b border-pslb-blue-800/10 transition-shadow duration-300">
    <div id="scrollBar" class="absolute bottom-0 left-0 h-[3px] w-full origin-left bg-pslb-cyan-500"
        style="transform: scaleX(0)"></div>

    <div class="container mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
        <a href="{{ route('accueil') }}" class="flex items-center">
            <img src="{{ asset('images/pslb-logo.png') }}" alt="PSLB — Votre besoin, notre solution"
                class="h-12 w-auto">
        </a>

        {{-- Desktop --}}
        <nav class="hidden lg:flex items-center gap-8 text-sm font-semibold text-pslb-blue-800">
            @foreach ($navLinks as $link)
                <a href="{{ route($link['route']) }}" @if ($isActive($link['route'])) aria-current="page" @endif @class([
                    'relative py-1 transition-colors hover:text-pslb-cyan-500',
                    'text-pslb-cyan-500 after:absolute after:left-0 after:-bottom-1 after:h-0.5 after:w-full after:rounded-full after:bg-pslb-cyan-500' => $isActive(
                        $link['route']
                    ),
                ])>
                    {{ $link['label'] }}
                </a>
            @endforeach
            <a href="{{ route('contact') }}"
                class="bg-pslb-blue-800 hover:bg-pslb-cyan-500 text-white px-5 py-2.5 rounded-full transition-colors">
                Nous contacter
            </a>
        </nav>

        <button id="menuBtn" class="burger lg:hidden relative w-10 h-10 text-pslb-blue-800" aria-label="Ouvrir le menu"
            aria-expanded="false" aria-controls="mobileMenu">
            <span></span><span></span><span></span>
        </button>
    </div>

    {{-- Mobile --}}
    <div id="mobileMenu" inert>
        <nav class="bg-white border-t border-pslb-border">
            <div class="px-4 py-4 space-y-1 text-sm font-semibold text-pslb-blue-800">
                @foreach ($navLinks as $link)
                    <a href="{{ route($link['route']) }}" @if ($isActive($link['route'])) aria-current="page" @endif
                        @class(['block py-2.5', 'text-pslb-cyan-500' => $isActive($link['route'])])>
                        {{ $link['label'] }}
                    </a>
                @endforeach
                <a href="{{ route('contact') }}"
                    class="block mt-2 text-center bg-pslb-blue-800 text-white py-3 rounded-full">Nous contacter</a>
            </div>
        </nav>
    </div>
</header>
