@props([
    'onclick' => '',
    'tip' => 'Supprimer',
])

<button type="button" onclick="{{ $onclick }}" data-tip="{{ $tip }}"
    class="w-[28px] h-[28px] rounded-md border border-black/[.09] dark:border-white/[.08]
           bg-[#f0f3f8] dark:bg-[#1e2940] text-gray-400 hover:text-red-500
           hover:border-red-400/40 hover:bg-red-500/10
           dark:hover:text-red-400 dark:hover:border-red-400/30 dark:hover:bg-red-500/10
           flex items-center justify-center transition-colors cursor-pointer">
    <svg class="w-[12px] h-[12px]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round"
            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7V4h6v3M4 7h16" />
    </svg>
</button>
