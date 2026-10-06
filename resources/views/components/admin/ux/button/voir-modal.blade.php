@props([
    'modal'    => '',
    'subtitle' => '',
    'tip'      => 'Voir',
    'fields'   => [],
])

<button
    data-view-modal="{{ $modal }}"
    data-view-subtitle="{{ $subtitle }}"
    @foreach ($fields as $key => $value)
        data-field-{{ $key }}="{{ $value }}"
    @endforeach
    data-tip="{{ $tip }}"
    class="w-[28px] h-[28px] rounded-md border border-black/[.09] dark:border-white/[.08]
           bg-[#f0f3f8] dark:bg-[#1e2940] text-gray-400 hover:text-violet-500
           flex items-center justify-center transition-colors cursor-pointer view-btn">
    <svg class="w-[12px] h-[12px]" fill="none" stroke="currentColor"
         stroke-width="2" viewBox="0 0 24 24">
        <path d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
    </svg>
</button>
