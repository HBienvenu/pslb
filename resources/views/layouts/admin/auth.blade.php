<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>PSLB | Verification Accès</title>
    <link rel="icon" href="{{ asset('images/pslb-logo.png') }}" type="image/x-icon">
    <meta name="description"
        content="PSLB : location de voiture, livraison de colis et courses, couverture médiatique d'événements. Votre besoin, notre solution.">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-white min-h-screen">

    <div class="min-h-screen flex items-center justify-center px-4">
        @yield('content')
        @include('layouts.admin.partials.alert')
    </div>

    <script type="module" src="{{ asset('js/site/script_module.js') }}"></script>
    <script src="{{ asset('js/site/script.js') }}"></script>
</body>

</html>
