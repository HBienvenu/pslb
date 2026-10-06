@props(['label', 'color' => 'bg-cdp-red-500'])

<div {{ $attributes->merge(['class' => 'flex items-center gap-2 px-1 mb-2 mt-1']) }}>
    <div class="w-[3px] h-[13px] rounded-full {{ $color }} shrink-0"></div>

    <span class="text-[10.5px] font-bold tracking-[.10em] uppercase
        text-gray-600 dark:text-[#7e8fba]">
        {{ $label }}
    </span>

    <div class="flex-1 h-px bg-black/[.06] dark:bg-white/[.06]"></div>
</div>
