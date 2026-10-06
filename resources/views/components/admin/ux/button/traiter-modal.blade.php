{{-- resources/views/components/admin/ux/button/traiter-modal.blade.php --}}

@props([
    'onclick' => '',
    'tip'     => 'Traiter',
])

<button type="button"
    @if ($onclick) onclick="{{ $onclick }}" @endif
    data-tip="{{ $tip }}"
    class="w-[28px] h-[28px] rounded-md border border-black/[.09] dark:border-white/[.08]
           bg-[#f0f3f8] dark:bg-[#1e2940] text-gray-400
           hover:text-green-600 hover:border-green-500/40 hover:bg-green-500/10
           dark:hover:text-green-400 dark:hover:border-green-400/30 dark:hover:bg-green-500/10
           flex items-center justify-center transition-colors cursor-pointer">
    <svg class="w-[12px] h-[12px]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
    </svg>
</button>
