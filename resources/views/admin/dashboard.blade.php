@extends('layouts.admin.app')

@section('content')
    {{-- ── En-tête ── --}}
    <div class="flex items-start justify-between mb-[18px]">
        <div>
            <h1 class="font-display text-[17px] md:text-[19px] font-bold text-gray-900 dark:text-[#e4e6eb] m-0">
                Tableau de bord
            </h1>
            <p class="text-xs text-gray-400 dark:text-[#5e6e8a] mt-[3px] m-0">
                {{ ucfirst(\Carbon\Carbon::now()->locale('fr')->isoFormat('dddd D MMMM YYYY')) }}
                — Vue d'ensemble des activités
            </p>
        </div>
        <div class="flex items-center gap-[5px] text-[11.5px] text-gray-400 dark:text-[#5e6e8a]">
            <span class="w-[7px] h-[7px] rounded-full bg-emerald-500 inline-block ld"></span>
            <span class="hidden sm:inline">Données en temps réel</span>
        </div>
    </div>

    {{-- Contenu --}}
@endsection
