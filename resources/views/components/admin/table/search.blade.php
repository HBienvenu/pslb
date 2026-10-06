@props([
    'placeholder' => 'Rechercher…',
    'id' => 'search-table',
])

<div class="relative flex-1 min-w-[180px]">
    <svg class="absolute left-[10px] top-1/2 -translate-y-1/2 w-[13px] h-[13px] text-gray-400 pointer-events-none"
        fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <circle cx="11" cy="11" r="8" />
        <path d="m21 21-4.35-4.35" />
    </svg>
    <input type="text" id="{{ $id }}" placeholder="{{ $placeholder }}"
        {{ $attributes->merge([
            'class' => 'w-full bg-[#f0f3f8] dark:bg-[#1e2940] border border-black/[.07]
                   dark:border-white/[.06] rounded-lg py-[7px] pl-[30px] pr-3 text-xs text-gray-900
                   dark:text-[#e4e6eb] outline-none transition-colors focus:border-cdp-red-500
                   placeholder:text-gray-400',
        ]) }} />
</div>
