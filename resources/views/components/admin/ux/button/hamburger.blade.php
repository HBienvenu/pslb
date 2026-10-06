@props([
    'onclick' => 'openSidebar()',
])
 
<button
    onclick="{{ $onclick }}"
    class="md:hidden w-[34px] h-[34px] rounded-lg border border-black/[.11] dark:border-white/[.10]
           bg-[#eef1f6] dark:bg-[#1e2940] text-gray-500 dark:text-[#909ab0]
           flex items-center justify-center cursor-pointer shrink-0">
    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <path d="M4 6h16M4 12h16M4 18h16" />
    </svg>
</button>