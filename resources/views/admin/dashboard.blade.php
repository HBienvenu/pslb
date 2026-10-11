@extends('layouts.admin.app', ['title' => 'Tableau de bord'])

@section('content')
    @php
        $cards = [
            [
                'label' => 'Réservations',
                'value' => $stats['reservations']['total'],
                'detail' => $stats['reservations']['en_attente'] . ' en attente',
                'accent' => 'cyan',
                'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M8 7V3m8 4V3M3 11h18M5 5h14a2 2 0 012 2v12a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2z"/></svg>',
            ],
            [
                'label' => 'Livraisons',
                'value' => $stats['livraisons']['total'],
                'detail' => $stats['livraisons']['en_attente'] . ' à traiter',
                'accent' => 'blue',
                'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 8h12v9H3V8zM15 10h3l3 3v4h-6v-7zM7 18a2 2 0 100-4 2 2 0 000 4zm10 0a2 2 0 100-4 2 2 0 000 4z"/></svg>',
            ],
            [
                'label' => 'Médias',
                'value' => $stats['demandes']['total'],
                'detail' => $stats['demandes']['en_attente'] . ' en attente',
                'accent' => 'violet',
                'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9zM15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>',
            ],
            [
                'label' => 'Messages',
                'value' => $stats['messages']['total'],
                'detail' => $stats['messages']['non_lus'] . ' non lus',
                'accent' => 'red',
                'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M7 8h10a2 2 0 012 2v7a2 2 0 01-2 2H7l-4 3v-3H7a2 2 0 01-2-2v-7a2 2 0 012-2z"/><path d="M8 11h8M8 15h5"/></svg>',
            ],
        ];

        $cardClasses = [
            'cyan' => 'bg-cyan-500/10 text-cyan-600 dark:text-cyan-300',
            'blue' => 'bg-blue-500/10 text-blue-600 dark:text-blue-300',
            'violet' => 'bg-violet-500/10 text-violet-600 dark:text-violet-300',
            'red' => 'bg-red-500/10 text-red-600 dark:text-red-300',
        ];

        $statusColors = [
            'en_attente' => 'bg-amber-500/10 text-amber-700 dark:text-amber-300',
            'confirmee' => 'bg-emerald-500/10 text-emerald-700 dark:text-emerald-300',
            'en_cours' => 'bg-cyan-500/10 text-cyan-700 dark:text-cyan-300',
            'livree' => 'bg-pslb-cyan-500/10 text-pslb-cyan-700 dark:text-pslb-cyan-300',
            'terminee' => 'bg-violet-500/10 text-violet-700 dark:text-violet-300',
            'refusee' => 'bg-red-500/10 text-red-700 dark:text-red-300',
            'annulee' => 'bg-slate-500/10 text-slate-700 dark:text-slate-300',
            'lu' => 'bg-pslb-cyan-500/10 text-pslb-cyan-700 dark:text-pslb-cyan-300',
            'non_lu' => 'bg-red-500/10 text-red-700 dark:text-red-300',
        ];

        $statusLabels = [
            'en_attente' => 'En attente',
            'confirmee' => 'Confirmée',
            'en_cours' => 'En cours',
            'livree' => 'Livrée',
            'terminee' => 'Terminée',
            'refusee' => 'Refusée',
            'annulee' => 'Annulée',
            'lu' => 'Lu',
            'non_lu' => 'Non lu',
        ];
    @endphp

    <div class="flex items-start justify-between mb-[18px] gap-3">
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
            <span class="w-[7px] h-[7px] rounded-full bg-emerald-500 inline-block"></span>
            <span class="hidden sm:inline">Données en temps réel</span>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 mb-6">
        @foreach ($cards as $card)
            <div class="rounded-2xl border border-pslb-border dark:border-white/[.08] bg-white dark:bg-pslb-blue-800 shadow-sm p-4">
                <div class="flex items-start justify-between gap-3 mb-3">
                    <div>
                        <p class="text-[11px] uppercase tracking-[0.08em] text-pslb-muted dark:text-pslb-blue-300 m-0">
                            {{ $card['label'] }}
                        </p>
                        <h2 class="mt-2 text-[28px] leading-none font-bold text-pslb-ink dark:text-pslb-blue-50 m-0">
                            {{ $card['value'] }}
                        </h2>
                    </div>
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center {{ $cardClasses[$card['accent']] ?? 'bg-slate-500/10 text-slate-600 dark:text-slate-300' }}">
                        {!! $card['icon'] !!}
                    </div>
                </div>
                <p class="text-[12px] text-pslb-muted dark:text-pslb-blue-200 m-0">
                    {{ $card['detail'] }}
                </p>
            </div>
        @endforeach
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-[1.3fr_0.7fr] gap-4 mb-6">
        <div class="rounded-2xl border border-pslb-border dark:border-white/[.08] bg-white dark:bg-pslb-blue-800 shadow-sm p-4">
            <div class="flex items-center justify-between gap-3 mb-4">
                <div>
                    <h3 class="text-[14px] font-semibold text-pslb-ink dark:text-pslb-blue-50 m-0">
                        Activité récente
                    </h3>
                    <p class="text-[11px] text-pslb-muted dark:text-pslb-blue-300 mt-1 m-0">
                        Dernières soumissions enregistrées
                    </p>
                </div>
                <a href="{{ route('admin.reservations.index') }}" class="text-[11px] text-pslb-blue-700 dark:text-pslb-cyan-300 hover:underline">
                    Voir tout
                </a>
            </div>

            <div class="space-y-3">
                @forelse ($recentActivity as $activity)
                    <a href="{{ $activity['url'] }}" class="flex items-center justify-between gap-3 rounded-xl border border-pslb-border dark:border-white/[.08] bg-pslb-surface dark:bg-pslb-blue-900 px-3 py-2.5 hover:border-pslb-cyan-500 transition-colors no-underline">
                        <div class="min-w-0 flex items-center gap-3">
                            <div class="w-9 h-9 rounded-full bg-pslb-cyan-500/10 flex items-center justify-center text-pslb-cyan-700 dark:text-pslb-cyan-300 text-[10px] font-bold shrink-0">
                                {{ strtoupper(substr($activity['type'], 0, 2)) }}
                            </div>
                            <div class="min-w-0">
                                <p class="truncate text-[12.5px] font-medium text-pslb-ink dark:text-pslb-blue-50 m-0">
                                    {{ $activity['title'] }}
                                </p>
                                <p class="text-[10.5px] text-pslb-muted dark:text-pslb-blue-300 m-0">
                                    {{ $activity['type'] }} • {{ $activity['label'] }}
                                </p>
                            </div>
                        </div>
                        <div class="flex flex-col items-end gap-1 shrink-0">
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-[10px] font-medium {{ $statusColors[$activity['status']] ?? 'bg-slate-500/10 text-slate-700 dark:text-slate-300' }}">
                                {{ $statusLabels[$activity['status']] ?? ucfirst(str_replace('_', ' ', $activity['status'])) }}
                            </span>
                            <span class="text-[10px] text-pslb-muted dark:text-pslb-blue-300">
                                {{ $activity['date']?->diffForHumans() ?? 'À l\'instant' }}
                            </span>
                        </div>
                    </a>
                @empty
                    <div class="rounded-xl border border-dashed border-pslb-border dark:border-white/[.08] bg-pslb-surface dark:bg-pslb-blue-900 p-4 text-center text-[12px] text-pslb-muted dark:text-pslb-blue-300">
                        Aucune activité récente.
                    </div>
                @endforelse
            </div>
        </div>

        <div class="rounded-2xl border border-pslb-border dark:border-white/[.08] bg-white dark:bg-pslb-blue-800 shadow-sm p-4">
            <h3 class="text-[14px] font-semibold text-pslb-ink dark:text-pslb-blue-50 m-0 mb-3">
                Vue d'ensemble
            </h3>
            <div class="space-y-4">
                <div>
                    <div class="flex justify-between text-[11.5px] text-pslb-muted dark:text-pslb-blue-300 mb-1">
                        <span>Réservations</span>
                        <span>{{ $stats['reservations']['total'] }}</span>
                    </div>
                    <div class="w-full h-2.5 rounded-full bg-slate-200 dark:bg-slate-700 overflow-hidden">
                        <div class="h-full rounded-full bg-cyan-500" style="width: {{ $stats['reservations']['total'] > 0 ? min(100, ($stats['reservations']['confirmees'] / max(1, $stats['reservations']['total'])) * 100) : 0 }}%"></div>
                    </div>
                </div>

                <div>
                    <div class="flex justify-between text-[11.5px] text-pslb-muted dark:text-pslb-blue-300 mb-1">
                        <span>Livraisons</span>
                        <span>{{ $stats['livraisons']['total'] }}</span>
                    </div>
                    <div class="w-full h-2.5 rounded-full bg-slate-200 dark:bg-slate-700 overflow-hidden">
                        <div class="h-full rounded-full bg-blue-500" style="width: {{ $stats['livraisons']['total'] > 0 ? min(100, ($stats['livraisons']['livrees'] / max(1, $stats['livraisons']['total'])) * 100) : 0 }}%"></div>
                    </div>
                </div>

                <div>
                    <div class="flex justify-between text-[11.5px] text-pslb-muted dark:text-pslb-blue-300 mb-1">
                        <span>Demande média</span>
                        <span>{{ $stats['demandes']['total'] }}</span>
                    </div>
                    <div class="w-full h-2.5 rounded-full bg-slate-200 dark:bg-slate-700 overflow-hidden">
                        <div class="h-full rounded-full bg-violet-500" style="width: {{ $stats['demandes']['total'] > 0 ? min(100, ($stats['demandes']['confirmees'] / max(1, $stats['demandes']['total'])) * 100) : 0 }}%"></div>
                    </div>
                </div>

                <div>
                    <div class="flex justify-between text-[11.5px] text-pslb-muted dark:text-pslb-blue-300 mb-1">
                        <span>Messages</span>
                        <span>{{ $stats['messages']['total'] }}</span>
                    </div>
                    <div class="w-full h-2.5 rounded-full bg-slate-200 dark:bg-slate-700 overflow-hidden">
                        <div class="h-full rounded-full bg-red-500" style="width: {{ $stats['messages']['total'] > 0 ? min(100, (($stats['messages']['lus'] ?? 0) / max(1, $stats['messages']['total'])) * 100) : 0 }}%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
        <div class="rounded-2xl border border-pslb-border dark:border-white/[.08] bg-white dark:bg-pslb-blue-800 shadow-sm p-4">
            <h3 class="text-[14px] font-semibold text-pslb-ink dark:text-pslb-blue-50 m-0 mb-4">
                Réservations par statut
            </h3>
            <div class="space-y-3">
                @foreach ([
                    'en_attente' => ['label' => 'En attente', 'value' => $stats['reservations']['en_attente'], 'color' => 'bg-amber-500'],
                    'confirmee' => ['label' => 'Confirmées', 'value' => $stats['reservations']['confirmees'], 'color' => 'bg-emerald-500'],
                    'terminee' => ['label' => 'Terminées', 'value' => $stats['reservations']['terminees'], 'color' => 'bg-violet-500'],
                ] as $key => $item)
                    @php $ratio = $stats['reservations']['total'] > 0 ? ($item['value'] / $stats['reservations']['total']) * 100 : 0; @endphp
                    <div>
                        <div class="flex items-center justify-between text-[12px] text-pslb-ink dark:text-pslb-blue-50 mb-1">
                            <span>{{ $item['label'] }}</span>
                            <span>{{ $item['value'] }}</span>
                        </div>
                        <div class="h-2.5 rounded-full bg-slate-200 dark:bg-slate-700 overflow-hidden">
                            <div class="h-full rounded-full {{ $item['color'] }}" style="width: {{ min(100, $ratio) }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="rounded-2xl border border-pslb-border dark:border-white/[.08] bg-white dark:bg-pslb-blue-800 shadow-sm p-4">
            <h3 class="text-[14px] font-semibold text-pslb-ink dark:text-pslb-blue-50 m-0 mb-4">
                Livraisons par statut
            </h3>
            <div class="space-y-3">
                @foreach ([
                    'en_attente' => ['label' => 'En attente', 'value' => $stats['livraisons']['en_attente'], 'color' => 'bg-amber-500'],
                    'confirmee' => ['label' => 'Confirmées', 'value' => $stats['livraisons']['confirmees'], 'color' => 'bg-emerald-500'],
                    'en_cours' => ['label' => 'En cours', 'value' => $stats['livraisons']['en_cours'] ?? 0, 'color' => 'bg-cyan-500'],
                    'livree' => ['label' => 'Livrées', 'value' => $stats['livraisons']['livrees'], 'color' => 'bg-blue-500'],
                ] as $key => $item)
                    @php $ratio = $stats['livraisons']['total'] > 0 ? ($item['value'] / $stats['livraisons']['total']) * 100 : 0; @endphp
                    <div>
                        <div class="flex items-center justify-between text-[12px] text-pslb-ink dark:text-pslb-blue-50 mb-1">
                            <span>{{ $item['label'] }}</span>
                            <span>{{ $item['value'] }}</span>
                        </div>
                        <div class="h-2.5 rounded-full bg-slate-200 dark:bg-slate-700 overflow-hidden">
                            <div class="h-full rounded-full {{ $item['color'] }}" style="width: {{ min(100, $ratio) }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
