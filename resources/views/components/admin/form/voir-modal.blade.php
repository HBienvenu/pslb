@props([
    'id' => 'modal-view',
    'title' => '—',
    'subtitle' => '',
    'iconColor' => 'violet',
    'maxWidth' => 'max-w-[460px]',
])

@php
    $colors = [
        'violet' => ['bg' => 'bg-violet-500/10', 'text' => 'text-violet-500'],
        'blue' => ['bg' => 'bg-blue-500/10', 'text' => 'text-blue-500'],
        'red' => ['bg' => 'bg-cdp-red-500/10', 'text' => 'text-cdp-red-500'],
        'green' => ['bg' => 'bg-green-500/10', 'text' => 'text-green-500'],
        'amber' => ['bg' => 'bg-amber-500/10', 'text' => 'text-amber-500'],
    ];
    $c = $colors[$iconColor] ?? $colors['violet'];
@endphp

<div id="{{ $id }}" class="fixed inset-0 z-[100] flex items-center justify-center p-4 hidden"
    onclick="handleModalBackdrop(event, '{{ $id }}')">

    <div class="absolute inset-0 bg-black/40 backdrop-blur-[2px]"></div>

    <div class="relative bg-white dark:bg-[#141c2e] rounded-2xl shadow-2xl
                border border-black/[.09] dark:border-white/[.07]
                w-full {{ $maxWidth }} max-h-[90vh] overflow-y-auto"
        onclick="event.stopPropagation()">

        {{-- ── Header ── --}}
        <div
            class="flex items-center justify-between px-5 py-4
                    border-b border-black/[.07] dark:border-white/[.06]">
            <div class="flex items-center gap-3">

                {{-- Icône --}}
                <div
                    class="w-[36px] h-[36px] rounded-full {{ $c['bg'] }}
                            flex items-center justify-center shrink-0">
                    <svg class="w-[16px] h-[16px] {{ $c['text'] }}" fill="none" stroke="currentColor"
                        stroke-width="2" viewBox="0 0 24 24">
                        <path d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955
                                 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824
                                 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622
                                 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                </div>

                {{-- Titre + sous-titre --}}
                <div>
                    <h3 data-view-subtitle-target
                        class="font-display text-[15px] font-bold text-gray-900 dark:text-[#e4e6eb] m-0">
                        {{ $title }}
                    </h3>
                    @if ($subtitle)
                        <p class="text-[11px] text-gray-400 dark:text-[#5e6e8a] mt-[2px] m-0">
                            {{ $subtitle }}
                        </p>
                    @endif
                </div>
            </div>

            {{-- Bouton fermer --}}
            <button onclick="closeModal('{{ $id }}')"
                class="w-[28px] h-[28px] rounded-lg border border-black/[.09] dark:border-white/[.08]
                           bg-[#f0f3f8] dark:bg-[#1e2940] text-gray-400 hover:text-gray-700
                           flex items-center justify-center cursor-pointer transition-colors">
                <svg class="w-[13px] h-[13px]" fill="none" stroke="currentColor" stroke-width="2.5"
                    viewBox="0 0 24 24">
                    <path d="M18 6L6 18M6 6l12 12" />
                </svg>
            </button>
        </div>

        {{-- ── Body ── --}}
        <div class="px-5 py-4 flex flex-col gap-4">
            {{ $slot }}
        </div>

        {{-- ── Footer ── --}}
        <div
            class="flex items-center justify-end px-5 py-3.5
                    border-t border-black/[.07] dark:border-white/[.06]">
            <button type="button" onclick="closeModal('{{ $id }}')"
                class="px-4 py-[7px] rounded-lg border border-black/[.10] dark:border-white/[.10]
                           bg-white dark:bg-[#1e2940] text-[12.5px] text-gray-600 dark:text-[#c9d1e0]
                           hover:bg-[#f0f3f8] dark:hover:bg-[#253352] cursor-pointer transition-colors">
                Fermer
            </button>
        </div>

    </div>
</div>
