@props([
    'id',
    'title' => 'Supprimer',
    'subtitle' => '',
    'subtitleId' => null,
    'action' => '',
    'formId' => 'delete-form',
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
                rounded-2xl shadow-xl overflow-hidden">

        {{-- En-tête --}}
        <div
            class="flex items-start justify-between gap-3 px-5 pt-5 pb-4
                    border-b border-black/[.06] dark:border-white/[.05]">
            <div class="flex items-center gap-3">
                {{-- Icône danger --}}
                <div class="w-[36px] h-[36px] rounded-xl bg-red-500/10 flex items-center justify-center shrink-0">
                    <svg class="w-[17px] h-[17px] text-red-500" fill="none" stroke="currentColor" stroke-width="2.2"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7V4h6v3M4 7h16" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-[13.5px] font-semibold text-gray-900 dark:text-[#e4e6eb] m-0 leading-tight">
                        {{ $title }}
                    </h3>
                    <p @if ($subtitleId) id="{{ $subtitleId }}" @endif
                        class="text-[11.5px] text-gray-400 dark:text-[#5e6e8a] m-0 mt-0.5 leading-tight">
                        {{ $subtitle }}
                    </p>
                </div>
            </div>
            {{-- Bouton fermer --}}
            <button type="button" onclick="closeModal('{{ $id }}')"
                class="w-[26px] h-[26px] rounded-lg flex items-center justify-center shrink-0
                       text-gray-400 hover:text-gray-600 hover:bg-black/[.05]
                       dark:text-[#5e6e8a] dark:hover:text-[#909ab0] dark:hover:bg-white/[.05]
                       transition-colors border-0 bg-transparent cursor-pointer mt-0.5">
                <svg class="w-[14px] h-[14px]" fill="none" stroke="currentColor" stroke-width="2.5"
                    viewBox="0 0 24 24">
                    <path d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        {{-- Corps —  message de confirmation --}}
        <div class="px-5 py-4">
            <p class="text-[12.5px] text-gray-600 dark:text-[#909ab0] leading-relaxed m-0">
                Cette action est <span class="font-semibold text-gray-800 dark:text-[#c8cdd8]">irréversible</span>.
                Voulez-vous vraiment supprimer ?
                <span class="font-semibold text-gray-800 dark:text-[#c8cdd8]"
                    @if ($subtitleId) id="{{ $subtitleId }}-confirm" @endif>
                    {{ $subtitle }}
                </span>
            </p>
        </div>

        {{-- Pied — boutons --}}
        <div class="px-5 pb-5 flex items-center justify-end gap-2">
            <button type="button" onclick="closeModal('{{ $id }}')"
                class="px-4 py-[7px] rounded-lg text-[12px] font-medium cursor-pointer
                       bg-black/[.05] hover:bg-black/[.09] dark:bg-white/[.05] dark:hover:bg-white/[.09]
                       text-gray-700 dark:text-[#909ab0] transition-colors border-0">
                Annuler
            </button>

            <form id="{{ $formId }}" action="{{ $action }}" method="POST" class="m-0">
                @csrf
                @method('DELETE')
                {{-- Champs cachés pour la réouverture après erreur --}}
                <input type="hidden" name="_modal" value="{{ $id }}">
                <input type="hidden" id="{{ $id }}-form-action" name="_form_action"
                    value="{{ $action }}">
                <input type="hidden" id="{{ $id }}-modal-subtitle" name="_modal_subtitle"
                    value="{{ $subtitle }}">

                <button type="submit"
                    class="px-4 py-[7px] rounded-lg text-[12px] font-medium cursor-pointer
                           bg-red-500 hover:bg-red-600 text-white transition-colors border-0">
                    Supprimer
                </button>
            </form>
        </div>
    </div>
</div>
