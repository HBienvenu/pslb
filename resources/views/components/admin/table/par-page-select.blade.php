@props([
    'selected' => 10,
    'options'  => [5, 10, 25, 50],
    'tsId'     => 'filter-per-page-select',
    'showAll'  => true,
])

<select id="{{ $tsId }}"
    {{ $attributes->except('id') }}
    class="bg-[#f0f3f8] dark:bg-[#1e2940] border border-black/[.07] dark:border-white/[.06]
           rounded-lg text-xs text-gray-700 dark:text-[#c9d1e0] outline-none
           focus:border-cdp-red-500 cursor-pointer min-w-[110px]">

    @foreach ($options as $value)
        <option value="{{ $value }}" @selected((int) $selected === (int) $value)>
            {{ $value }} / page
        </option>
    @endforeach

    @if ($showAll)
        <option value="-1" @selected((int) $selected === -1)>Tout afficher</option>
    @endif
</select>
