@props([
    'root'  => 'Back Office',
    'title' => '',
])

<div class="flex items-center gap-[5px] text-xs text-gray-400 dark:text-[#5e6e8a] shrink-0">
    <span class="hidden lg:inline">{{ $root }}</span>
    <svg class="hidden lg:block w-[11px] h-[11px]" fill="none" stroke="currentColor"
         stroke-width="2.5" viewBox="0 0 24 24">
        <path d="M9 5l7 7-7 7" />
    </svg>
    <span class="text-gray-900 dark:text-[#e4e6eb] font-medium">{{ html_entity_decode($title) }}</span>
</div>
