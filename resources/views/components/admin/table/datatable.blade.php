@props([
    'columns' => [],
    'tableId' => 'datatable',
    'minWidth' => '600px',
    'emptyTitle' => 'Aucun résultat trouvé',
    'emptySubtitle' => 'Modifiez vos critères de recherche',
    'showEmpty' => false,
    'showPagination' => true,
    'itemLabel' => 'éléments',
    'pagFromId'     => 'pag-from',
    'pagToId'       => 'pag-to',
    'pagTotalId'    => 'pag-total',
    'pagButtonsId'  => 'pagination-buttons',
])

<div
    class="bg-white dark:bg-[#141c2e] border border-black/[.07] dark:border-white/[.06]
            rounded-xl shadow-sm overflow-hidden mb-4">
    <div class="overflow-x-auto">
        <table id="{{ $tableId }}" class="w-full text-xs border-collapse" style="min-width:{{ $minWidth }}">

            <thead>
                <tr class="border-b border-black/[.07] dark:border-white/[.06] bg-[#f7f9fc] dark:bg-[#0f1828]">
                    @foreach ($columns as $column)
                        <th data-sort="{{ is_array($column) ? $column['sort'] ?? null : null }}"
                            class="{{ $loop->first || $loop->last ? 'px-4' : 'px-3' }}
                                   {{ $loop->last ? 'text-right' : 'text-left' }}
                                   py-[10px] font-semibold text-[10.5px] text-gray-500
                                   dark:text-[#5e6e8a] tracking-[.04em]">
                            {{ is_array($column) ? $column['label'] : $column }}
                        </th>
                    @endforeach
                </tr>
            </thead>

            <tbody id="table-tbody">
                {{ $slot }}
            </tbody>

        </table>
    </div>

    {{-- Empty state --}}
    <div id="empty-state" @class([
        'flex flex-col items-center justify-center py-14 text-center',
        'hidden' => !$showEmpty,
    ])>
        <div class="w-[48px] h-[48px] rounded-full bg-gray-100 dark:bg-[#1e2940] flex items-center justify-center mb-3">
            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" stroke-width="1.5"
                viewBox="0 0 24 24">
                <path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
        </div>
        <p class="text-[13px] font-medium text-gray-500 dark:text-[#909ab0] m-0">{{ $emptyTitle }}</p>
        <p class="text-[11.5px] text-gray-400 dark:text-[#5e6e8a] mt-1 m-0">{{ $emptySubtitle }}</p>
    </div>
</div>

{{-- Pagination --}}
@if ($showPagination)
    <div class="flex items-center justify-between text-[11.5px] text-gray-400 dark:text-[#5e6e8a]">
        <span>
            Affichage de
            <strong id="{{ $pagFromId }}" class="text-gray-700 dark:text-[#c9d1e0]">1</strong>–<strong id="{{ $pagToId }}"
                class="text-gray-700 dark:text-[#c9d1e0]">–</strong>
            sur
            <strong id="{{ $pagTotalId }}" class="text-gray-700 dark:text-[#c9d1e0]">–</strong>
            {{ $itemLabel }}
        </span>
        <div id="{{ $pagButtonsId }}" class="flex items-center gap-1"></div>
    </div>
@endif
