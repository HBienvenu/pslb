@extends('layouts.admin.app', ['title' => 'Profil utilisateur'])

@section('content')
    @php
        $lbl = 'block text-[11.5px] font-medium text-pslb-muted dark:text-pslb-blue-200 mb-1.5';
        $base = 'w-full bg-pslb-surface dark:bg-pslb-blue-900 border rounded-lg py-[8px] px-3 text-[12.5px] text-pslb-ink dark:text-pslb-blue-50 outline-none transition-colors placeholder:text-pslb-muted';
        $cls = fn($name) => $base . ' ' . ($errors->has($name)
            ? 'border-red-400 dark:border-red-500 focus:border-red-500'
            : 'border-pslb-border dark:border-white/[.08] focus:border-pslb-cyan-500');
    @endphp

    <div class="max-w-3xl">
        <x-admin.page-header title="Profil utilisateur" description="Mettez à jour vos informations personnelles et votre mot de passe" />

        @if (session('success'))
            <div class="mb-4 rounded-lg border border-emerald-200 bg-emerald-50 px-3 py-2 text-[12px] text-emerald-700 dark:border-emerald-500/30 dark:bg-emerald-500/10 dark:text-emerald-300">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('admin.profil.update') }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')

            <div class="bg-white dark:bg-pslb-blue-800 border border-pslb-border dark:border-white/[.08] rounded-xl p-4 shadow-sm">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="name" class="{{ $lbl }}">Nom complet <span class="text-red-500">*</span></label>
                        <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}" class="{{ $cls('name') }}" required>
                        @error('name')
                            <p class="mt-1.5 text-[11px] text-red-500 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="{{ $lbl }}">Adresse e-mail <span class="text-red-500">*</span></label>
                        <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" class="{{ $cls('email') }}" required>
                        @error('email')
                            <p class="mt-1.5 text-[11px] text-red-500 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <div id="mot-de-passe" class="bg-white dark:bg-pslb-blue-800 border border-pslb-border dark:border-white/[.08] rounded-xl p-4 shadow-sm">
                <h2 class="text-[13px] font-semibold text-pslb-ink dark:text-pslb-blue-50 mb-4">Sécurité</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="current_password" class="{{ $lbl }}">Mot de passe actuel</label>
                        <input id="current_password" name="current_password" type="password" placeholder="••••••••" class="{{ $cls('current_password') }}">
                        @error('current_password')
                            <p class="mt-1.5 text-[11px] text-red-500 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div></div>

                    <div>
                        <label for="password" class="{{ $lbl }}">Nouveau mot de passe</label>
                        <input id="password" name="password" type="password" placeholder="Minimum 8 caractères" class="{{ $cls('password') }}">
                        @error('password')
                            <p class="mt-1.5 text-[11px] text-red-500 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="{{ $lbl }}">Confirmer le mot de passe</label>
                        <input id="password_confirmation" name="password_confirmation" type="password" placeholder="Répétez le nouveau mot de passe" class="{{ $cls('password_confirmation') }}">
                    </div>
                </div>
            </div>

            <div class="flex justify-end">
                <button type="submit" class="inline-flex items-center justify-center gap-2 px-4 py-2 rounded-lg bg-pslb-blue-800 hover:bg-pslb-blue-700 text-white text-[13px] font-medium cursor-pointer transition-colors border-0">
                    Enregistrer les modifications
                </button>
            </div>
        </form>
    </div>
@endsection
