@props([
    'onClick' => null,
    'iconClass' => 'w-4 h-4',
])

<button type="button" @if ($onClick) onclick="{{ $onClick }}" @endif
    {{ $attributes->merge([
        'class' => 'ml-auto md:hidden w-7 h-7 flex items-center justify-center rounded-md
            text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 hover:bg-black/5',
    ]) }}>
    <svg class="{{ $iconClass }}" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
        <path d="M18 6L6 18M6 6l12 12" />
    </svg>
</button>
