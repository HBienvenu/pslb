{{-- page-header.blade.php --}}
@props(['title', 'description' => null, 'count' => null, 'countLabel' => null])

<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-5">
    <div>
        <h1 class="font-display text-[19px] font-bold text-gray-900 dark:text-[#e4e6eb] m-0">
            {{ $title }}
        </h1>
        @if ($description || $count)
            <p class="text-xs text-gray-400 dark:text-[#5e6e8a] mt-[3px] m-0">
                @if ($description)
                    {{ $description }}
                @endif
                @if ($description && $count)
                    —
                @endif
                @if ($count)
                    <span class="font-medium text-gray-600 dark:text-[#909ab0]">
                        {{ $count }}{{ $countLabel ? ' ' . $countLabel : '' }}
                    </span>
                @endif
            </p>
        @endif
    </div>

    <div class="shrink-0">{{ $slot }}</div>
</div>
