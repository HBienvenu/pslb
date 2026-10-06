@extends('layouts.admin.auth', ['title' => 'Réinitialisation du mot de passe'])

@section('content')
    <div class="min-h-screen flex items-center justify-center  px-4">
        <div class="shadow-2xl rounded-2xl w-full max-w-md p-8">

            <!-- Logo -->
            <div class="text-center mb-6">
                <img src="{{ asset('images/pslb-logo.png') }}" alt="AppSchool" class="w-32 mx-auto mb-4">
                <h1 class="text-xl font-bold text-blue-700">
                    Réinitialisation du mot de passe
                </h1>
                <p class="text-gray-600 text-sm mt-2">
                    Choisissez un nouveau mot de passe sécurisé pour votre compte.
                </p>
            </div>

            <!-- Formulaire -->
            <form method="POST" action="{{ route('password.renitialise.update') }}">
                @csrf

                <!-- Token caché -->
                <input type="hidden" name="token" value="{{ $token }}">

                <!-- Email -->
                <div class="mb-4">
                    <label for="email" class="block text-gray-600 text-sm mb-1">
                        Adresse email
                    </label>
                    <input type="email" id="email" name="email" value="{{ old('email', request('email')) }}"
                        required
                        class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:outline-none">

                    @error('email')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Nouveau mot de passe -->
                <div class="mb-4">
                    <label for="password" class="block text-gray-600 text-sm mb-1">
                        Nouveau mot de passe
                    </label>
                    <div class="relative">
                        <input type="password" id="password" name="password" required
                            class="w-full border border-gray-300 rounded-lg p-3 pr-11 focus:ring-2 focus:ring-blue-500 focus:outline-none">

                        <button type="button"
                            class="toggle-password absolute inset-y-0 right-0 flex items-center pr-3 text-gray-500 hover:text-gray-700"
                            data-target="password" aria-label="Afficher le mot de passe">
                            <!-- icône œil ouvert -->
                            <svg class="icon-eye w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <!-- icône œil barré -->
                            <svg class="icon-eye-off w-5 h-5 hidden" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />
                            </svg>
                        </button>
                    </div>

                    @error('password')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Confirmation -->
                <div class="mb-6">
                    <label for="password_confirmation" class="block text-gray-600 text-sm mb-1">
                        Confirmer le mot de passe
                    </label>
                    <div class="relative">
                        <input type="password" id="password_confirmation" name="password_confirmation" required
                            class="w-full border border-gray-300 rounded-lg p-3 pr-11 focus:ring-2 focus:ring-blue-500 focus:outline-none">

                        <button type="button"
                            class="toggle-password absolute inset-y-0 right-0 flex items-center pr-3 text-gray-500 hover:text-gray-700"
                            data-target="password_confirmation" aria-label="Afficher le mot de passe">
                            <svg class="icon-eye w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <svg class="icon-eye-off w-5 h-5 hidden" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Bouton -->
                <button type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-lg transition">
                    Réinitialiser le mot de passe
                </button>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('turbo:load', initTogglePassword);
        document.addEventListener('DOMContentLoaded', initTogglePassword);

        function initTogglePassword() {
            document.querySelectorAll('.toggle-password').forEach(function(btn) {
                // évite d'attacher le listener plusieurs fois (Turbo)
                if (btn.dataset.bound === 'true') return;
                btn.dataset.bound = 'true';

                btn.addEventListener('click', function() {
                    const targetId = btn.getAttribute('data-target');
                    const input = document.getElementById(targetId);
                    const eyeIcon = btn.querySelector('.icon-eye');
                    const eyeOffIcon = btn.querySelector('.icon-eye-off');

                    const isPassword = input.type === 'password';
                    input.type = isPassword ? 'text' : 'password';

                    eyeIcon.classList.toggle('hidden', isPassword);
                    eyeOffIcon.classList.toggle('hidden', !isPassword);
                    btn.setAttribute('aria-label', isPassword ? 'Masquer le mot de passe' :
                        'Afficher le mot de passe');
                });
            });
        }
    </script>
@endsection
