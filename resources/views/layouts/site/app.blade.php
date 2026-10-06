<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PSLB | {{ $title ?? "Votre Besoin, Notre Solution" }}</title>
    <link rel="icon" type="image/jpeg" href="{{ asset('images/pslb-logo.png') }}">
    @yield('meta')
    <meta name="description"
        content="PSLB : location de voiture, livraison de colis et courses, couverture médiatique d'événements. Votre besoin, notre solution.">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">
    @vite(['resources/css/site.css', 'resources/js/site.js'])
</head>

<body class="font-pslb overflow-x-hidden text-pslb-ink bg-white antialiased">

    @include('layouts.site.partials.header')

    @yield('content')

    @include('layouts.site.partials.footer')

    <script>
        (function () {
            const header = document.getElementById('siteHeader');
            const bar = document.getElementById('scrollBar');
            const onScroll = () => {
                const h = document.documentElement;
                const max = h.scrollHeight - h.clientHeight;
                const p = max > 0 ? h.scrollTop / max : 0;
                bar.style.transform = 'scaleX(' + p + ')';
                header.classList.toggle('shadow-md', h.scrollTop > 10);
            };
            window.addEventListener('scroll', onScroll, { passive: true });
            onScroll();
        })();
    </script>

    <script>
        const btn = document.getElementById('menuBtn'), menu = document.getElementById('mobileMenu');
        const setMenu = (open) => {
            menu.classList.toggle('open', open);
            menu.inert = !open;
            btn.setAttribute('aria-expanded', open);
            btn.setAttribute('aria-label', open ? 'Fermer le menu' : 'Ouvrir le menu');
        };
        btn.addEventListener('click', () => setMenu(!menu.classList.contains('open')));
        menu.querySelectorAll('a').forEach(a => a.addEventListener('click', () => setMenu(false)));
        document.addEventListener('keydown', e => { if (e.key === 'Escape') setMenu(false); });
        document.addEventListener('click', e => {
            if (!e.target.closest('#siteHeader')) setMenu(false);   // clic en dehors
        });
        window.addEventListener('resize', () => { if (innerWidth >= 1024) setMenu(false); });
    </script>

</body>

</html>
