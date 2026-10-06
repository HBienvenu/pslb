{{-- resources/views/components/admin/form/confirmer-action.blade.php --}}

@props([
    'id' => 'modal-confirm',
    'title' => 'Confirmer l\'action',
    'subtitleId' => 'confirm-modal-subtitle',
    'subtitle' => 'Êtes-vous sûr de vouloir effectuer cette action ?',
    'action' => '',
    'formId' => 'confirm-form',
    'method' => 'PUT',
    'submitLabel' => 'Confirmer',
    'submitColor' => 'bg-green-500 hover:bg-green-600',
    'iconColor' => 'green',
    'maxWidth' => 'max-w-[420px]',
])

{{-- Overlay --}}
<div id="{{ $id }}" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4" role="dialog"
    aria-modal="true">

    {{-- Backdrop --}}
    <div class="absolute inset-0 bg-black/40 dark:bg-black/60 backdrop-blur-[2px]"
        onclick="closeModal('{{ $id }}')"></div>

    {{-- Panneau --}}
    <div
        class="relative w-full {{ $maxWidth }} bg-white dark:bg-[#141c2e]
                border border-black/[.07] dark:border-white/[.06]
                rounded-2xl shadow-xl p-5">

        {{-- Bouton fermer --}}
        <button type="button" onclick="closeModal('{{ $id }}')"
            class="absolute top-3.5 right-3.5 w-[26px] h-[26px] rounded-lg
                   flex items-center justify-center
                   text-gray-400 hover:text-gray-600 hover:bg-black/[.05]
                   dark:text-[#5e6e8a] dark:hover:text-[#909ab0] dark:hover:bg-white/[.05]
                   transition-colors border-0 bg-transparent cursor-pointer">
            <svg class="w-[13px] h-[13px]" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>

        {{-- Icône + Titre + Subtitle --}}
        <div class="flex items-start gap-4">
            <div @class([
                'w-[38px] h-[38px] rounded-full flex items-center justify-center shrink-0',
                'bg-green-500/10 text-green-500' => $iconColor === 'green',
                'bg-blue-500/10 text-blue-500' => $iconColor === 'blue',
                'bg-amber-500/10 text-amber-500' => $iconColor === 'amber',
            ])>
                <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" stroke-width="2"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                </svg>
            </div>

            <div class="flex-1 min-w-0 pt-0.5">
                <h3 class="text-[14px] font-semibold text-gray-900 dark:text-[#e4e6eb] m-0 leading-tight">
                    {{ $title }}
                </h3>
                <p id="{{ $subtitleId }}"
                    class="mt-1 text-[12.5px] text-gray-500 dark:text-[#909ab0] m-0 leading-relaxed">
                    {{ $subtitle }}
                </p>
            </div>
        </div>

        {{-- Formulaire --}}
        <form id="{{ $formId }}" method="POST" action="{{ $action }}" class="mt-5 m-0">
            @csrf
            @method($method)
            <input type="hidden" id="{{ $id }}-form-action" name="_form_action" value="{{ $action }}">
            <input type="hidden" id="{{ $id }}-modal-subtitle" name="_modal_subtitle"
                value="{{ $subtitle }}">

            <div class="flex items-center justify-end gap-2.5">
                <button type="button" onclick="closeModal('{{ $id }}')"
                    class="px-4 py-[7px] rounded-lg text-[12.5px] font-medium cursor-pointer
                           bg-black/[.05] hover:bg-black/[.09] dark:bg-white/[.05] dark:hover:bg-white/[.09]
                           text-gray-700 dark:text-[#909ab0] transition-colors border-0">
                    Annuler
                </button>
                <button type="submit" @class([
                    'px-4 py-[7px] rounded-lg text-[12.5px] font-medium text-white transition-colors cursor-pointer border-0',
                    $submitColor,
                ])>
                    {{ $submitLabel }}
                </button>
            </div>
        </form>

    </div>
</div>
