@props([
    'id' => 'modal',
    'title' => '',
    'subtitle' => '',
    'action' => '#',
    'submitLabel' => 'Enregistrer',
    'maxWidth' => 'max-w-[460px]',
    'enctype' => null,
])

@php
    $shouldReopen = $errors->getBag('default')->any() && old('_modal') === $id;
@endphp

<div id="{{ $id }}" class="fixed inset-0 z-[100] flex items-center justify-center p-4 hidden"
    onclick="handleModalBackdrop(event, '{{ $id }}')">

    <div class="absolute inset-0 bg-black/40 backdrop-blur-[2px]"></div>

    <div class="relative bg-white dark:bg-[#141c2e] rounded-2xl shadow-2xl
                border border-black/[.09] dark:border-white/[.07]
                w-full {{ $maxWidth }} max-h-[90vh] overflow-y-auto"
        onclick="event.stopPropagation()">

        {{-- Header --}}
        <div
            class="flex items-center justify-between px-5 py-4
                    border-b border-black/[.07] dark:border-white/[.06]">
            <div>
                @if ($title)
                    <h3 class="font-display text-[15px] font-bold text-gray-900 dark:text-[#e4e6eb] m-0">
                        {{ $title }}
                    </h3>
                @endif
                @if ($subtitle)
                    <p class="text-[11px] text-gray-400 dark:text-[#5e6e8a] mt-[2px] m-0">
                        {{ $subtitle }}
                    </p>
                @endif
            </div>
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

        {{-- Formulaire --}}
        <form action="{{ $action }}" method="POST"
            @if ($enctype) enctype="{{ $enctype }}" @endif>
            @csrf

            {{-- Champs sentinels injectés automatiquement --}}
            <input type="hidden" name="_modal" value="{{ $id }}">
            <input type="hidden" name="_error_bag" value="default">

            <div class="px-5 py-4 flex flex-col gap-4">
                {{ $slot }}

                @if ($shouldReopen)
                    <div
                        class="flex items-start gap-2 p-3 rounded-lg bg-red-50 dark:bg-red-900/20
                                border border-red-200 dark:border-red-800">
                        <svg class="w-[14px] h-[14px] text-red-500 shrink-0 mt-[1px]" fill="currentColor"
                            viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0
                                     1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                clip-rule="evenodd" />
                        </svg>
                        <div>
                            <p class="text-[11.5px] font-medium text-red-700 dark:text-red-400 m-0">
                                Veuillez corriger les erreurs suivantes :
                            </p>
                            <ul class="mt-1 space-y-0.5">
                                @foreach ($errors->getBag('default')->all() as $error)
                                    <li class="text-[11px] text-red-600 dark:text-red-400">— {{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif
            </div>

            {{-- Footer --}}
            <div
                class="flex items-center justify-end gap-2.5 px-5 py-3.5
                    border-t border-black/[.07] dark:border-white/[.06]">
                <button type="button" onclick="closeModal('{{ $id }}')"
                    class="px-4 py-[7px] rounded-lg border border-black/[.10] dark:border-white/[.10]
                    bg-white dark:bg-[#1e2940] text-[12.5px] text-gray-600 dark:text-[#c9d1e0]
                    hover:bg-[#f0f3f8] dark:hover:bg-[#253352] cursor-pointer transition-colors">
                    Annuler
                </button>
                <button type="submit" id="{{ $id }}-submit-btn"
                    class="inline-flex items-center gap-2 px-4 py-[7px] rounded-lg bg-cdp-red-500 hover:bg-cdp-red-600
                    text-white text-[12.5px] font-medium cursor-pointer transition-colors border-0">
                    <svg id="{{ $id }}-submit-spinner" class="hidden w-[13px] h-[13px] animate-spin"
                        fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                            stroke-width="3" />
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z" />
                    </svg>
                    <span id="{{ $id }}-submit-label">{{ $submitLabel }}</span>
                </button>
            </div>
        </form>
    </div>
</div>

@if ($shouldReopen)
    <script>
        document.addEventListener('turbo:load', function() {
            openModal('{{ $id }}');
        }, {
            once: true
        });
    </script>
@endif
