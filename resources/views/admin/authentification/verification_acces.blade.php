@extends('layouts.admin.auth', ['title' => 'Verification Accès'])

@section('content')
    <div class="w-full max-w-lg flex flex-col items-center gap-4">

        @if ($errors->any())
            <div class="relative w-full p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg text-left">
                <button type="button" class="absolute top-2 right-2 text-red-500 hover:text-red-700"
                    onclick="this.parentElement.style.display='none'">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
                <ul class="list-disc list-inside space-y-1 pr-6">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if ($user->active)
            <script>
                window.location.href = "{{ route('dashboard') }}";
            </script>
        @else
            <div class="bg-white shadow-2xl rounded-2xl w-full p-8 text-center">
                <img src="{{ asset('images/pslb-logo.png') }}" alt="Logo" class="w-40 mx-auto mb-4">
                <h1 class="font-bold text-blue-700 mb-2">Verification d'accès</h1>
                <p class="text-gray-600 text-sm mb-6">
                    Votre compte n'est pas actif.<br>

                    <br>Equipe PSLB <a href="mailto:info@cdpatafrique.com"
                        class="text-blue-500">info@cdpatafrique.com</a>.
                </p>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg transition">
                        Revenir en arrière
                    </button>
                </form>
            </div>
        @endif
    </div>
@endsection
