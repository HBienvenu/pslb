@props([
    'onclick' => '',
    'tip'     => 'Modifier',
])

<button
    onclick="{{ $onclick }}"
    data-tip="{{ $tip }}"
    class="w-[28px] h-[28px] rounded-md border border-black/[.09] dark:border-white/[.08]
           bg-[#f0f3f8] dark:bg-[#1e2940] text-gray-400 hover:text-cdp-blue-500
           flex items-center justify-center transition-colors cursor-pointer">
    <svg class="w-[12px] h-[12px]" fill="none" stroke="currentColor"
         stroke-width="2" viewBox="0 0 24 24">
        <path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
    </svg>
</button>
