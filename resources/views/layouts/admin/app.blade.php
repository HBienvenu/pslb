<!DOCTYPE html>
<html lang="fr" class="light">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <script src="{{ asset('js/admin/script.js') }}"></script>
    <title>PSLB - {{ $title ?? 'Back Office Admin' }}</title>
    <link rel="icon" href="{{ asset('images/pslb-logo.png') }}" type="image/x-icon">

    @vite(['resources/css/admin.css', 'resources/js/admin.js'])

    <link
        href="https://fonts.googleapis.com/css2?family=Syne:wght@400;500;600;700;800&family=DM+Sans:wght@300;400;500;600&display=swap"
        rel="stylesheet" />
</head>

<body class="bg-[#f0f3f8] dark:bg-[#0b1120] text-gray-900 dark:text-[#e4e6eb]">
    <div class="flex h-screen overflow-hidden" id="app-root">

        {{-- Sidebar --}}
        @include('layouts.admin.partials.sidebar')

        <!-- MAIN -->
        <div class="flex-1 flex flex-col min-w-0 overflow-hidden" id="main-wrap">

            <!-- TOPBAR -->
            @include('layouts.admin.partials.header')

            <!-- CONTENT -->
            <main class="flex-1 overflow-y-auto p-3.5 md:p-5.5 bg-[#f0f3f8] dark:bg-[#0b1120]">
                @yield('content')
            </main>

            <!-- FOOTER -->
            @include('layouts.admin.partials.footer')

            @include('layouts.admin.partials.alert')
        </div>
    </div>

    <script src="{{ asset('js/admin/utilities.js') }}"></script>
</body>

</html>
