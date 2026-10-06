@extends('layouts.admin.app', ['title' => 'Catégories de véhicules'])

@section('content')
    <style>
        #filter-per-page-select+.ts-wrapper {
            min-width: 160px;
            max-width: 220px;
            width: auto;
        }
    </style>
    @php
        $fromAdd = old('_modal') === 'modal-add-categorie';
        $fromEdit = old('_modal') === 'modal-edit-categorie';
    @endphp

    {{-- ══ En-tête de page ══ --}}
    <x-admin.page-header title="Catégories de véhicules" description="Gestion des catégories de véhicules" :count="$categories->count()"
        count-label="catégories">
        <button onclick="openModal('modal-add-categorie')"
            class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-pslb-blue-800 hover:bg-pslb-blue-700
                   text-white text-[13px] font-medium cursor-pointer transition-colors border-0">
            <svg class="w-[14px] h-[14px]" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path d="M12 4v16m8-8H4" />
            </svg>
            Ajouter une catégorie
        </button>
    </x-admin.page-header>

    {{-- ══ Barre de filtres ══ --}}
    <div
        class="bg-white dark:bg-pslb-blue-800 border border-pslb-border dark:border-white/[.08]
            rounded-xl shadow-sm p-3.5 mb-4 flex flex-wrap gap-2.5 items-center">
        <x-admin.table.search />
        <x-admin.table.par-page-select />
        <span id="result-count" class="ml-auto text-[11.5px] text-pslb-muted dark:text-pslb-blue-300 shrink-0">
            {{ $categories->count() }}
        </span>
    </div>

    {{-- Tableau catégories --}}
    <x-admin.table.datatable :columns="[
        ['label' => 'CATÉGORIE', 'sort' => 'name'],
        ['label' => 'DESCRIPTION'],
        ['label' => 'VÉHICULES'],
        ['label' => 'ACTIONS'],
    ]" tableId="datatable-categories" item-label="catégories">
        @foreach ($categories as $categorie)
            <tr class="tr-hov border-b border-pslb-border dark:border-white/[.08] table-row"
                data-name="{{ $categorie->nom }}">
                <td class="px-4 py-[11px]">
                    <div class="flex items-center gap-[10px]">
                        <div
                            class="w-[32px] h-[32px] rounded-full bg-pslb-cyan-500/15
                                    flex items-center justify-center
                                    text-pslb-cyan-700 dark:text-pslb-cyan-300 font-display text-[10px] font-bold shrink-0">
                            {{ getInitialesStartEnd($categorie->nom) }}
                        </div>
                        <div>
                            <p class="font-medium text-[12.5px] text-pslb-ink dark:text-pslb-blue-50 m-0">
                                {{ $categorie->nom }}
                            </p>
                            <p class="text-[10px] text-pslb-muted dark:text-pslb-blue-300 m-0 sm:hidden">
                                {{ ucfirst($categorie->description ?? '') }}
                            </p>
                        </div>
                    </div>
                </td>
                <td class="px-3 py-[11px] text-pslb-muted dark:text-pslb-blue-200 hidden sm:table-cell">
                    {{ ucfirst($categorie->description ?? '') }}
                </td>
                <td class="px-3 py-[11px]">
                    <span
                        class="inline-flex items-center justify-center min-w-[22px] h-[20px] px-2 rounded-full
                               bg-pslb-blue-50 text-pslb-blue-700 dark:bg-pslb-blue-700 dark:text-pslb-blue-50
                               text-[11px] font-semibold">
                        {{ $categorie->vehicules_count }}
                    </span>
                </td>
                <td class="px-4 py-[11px] text-right">
                    <div class="flex items-center justify-end gap-1.5">
                        <x-admin.ux.button.modifier-modal
                            onclick="openEditModal({{ $categorie->id }}, {{ Js::from(ucfirstUtf8($categorie->nom)) }}, {{ Js::from($categorie->description ?? '') }})" />
                        <x-admin.ux.button.voir-modal modal="modal-view-categorie"
                            subtitle="{{ ucfirstUtf8($categorie->nom) }}" tip="Voir la catégorie" :fields="[
                                'nom' => $categorie->nom,
                                'description' => $categorie->description,
                            ]" />
                        <x-admin.ux.button.supprimer-modal
                            onclick="openDeleteModal({{ $categorie->id }}, {{ Js::from(ucfirstUtf8($categorie->nom)) }})" />
                    </div>
                </td>
            </tr>
        @endforeach
    </x-admin.table.datatable>

    {{-- ══ MODAL — Ajouter une catégorie ══ --}}
    <x-admin.form.ajout-modal id="modal-add-categorie" title="Ajouter une catégorie"
        subtitle="Définissez la nouvelle catégorie de véhicules" :action="route('admin.categories-vehicule.store')"
        submitLabel="Créer la catégorie" maxWidth="max-w-[460px]">

        <div>
            <label class="block text-[11.5px] font-medium text-pslb-muted dark:text-pslb-blue-200 mb-1.5">
                Nom de la catégorie <span class="text-red-500">*</span>
            </label>
            <input type="text" name="nom" value="{{ $fromAdd ? old('nom') : '' }}" placeholder="Ex : SUV"
                class="w-full bg-pslb-surface dark:bg-pslb-blue-900 border rounded-lg py-[8px] px-3 text-[12.5px]
                       text-pslb-ink dark:text-pslb-blue-50 outline-none transition-colors placeholder:text-pslb-muted
                       @error('nom') border-red-400 dark:border-red-500 focus:border-red-500
                       @else border-pslb-border dark:border-white/[.08] focus:border-pslb-cyan-500 @enderror"
                required />
            @error('nom')
                <p class="mt-1.5 text-[11px] text-red-500 dark:text-red-400 flex items-center gap-1">
                    <svg class="w-[11px] h-[11px] shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                            clip-rule="evenodd" />
                    </svg>
                    {{ $message }}
                </p>
            @enderror
        </div>

        <div>
            <label class="block text-[11.5px] font-medium text-pslb-muted dark:text-pslb-blue-200 mb-1.5">
                Description
            </label>
            <textarea name="description" rows="2" placeholder="Décrivez cette catégorie de véhicules…"
                class="w-full bg-pslb-surface dark:bg-pslb-blue-900 border rounded-lg py-[8px] px-3 text-[12.5px]
                       text-pslb-ink dark:text-pslb-blue-50 outline-none transition-colors placeholder:text-pslb-muted resize-none
                       @error('description') border-red-400 dark:border-red-500 focus:border-red-500
                       @else border-pslb-border dark:border-white/[.08] focus:border-pslb-cyan-500 @enderror">{{ $fromAdd ? old('description') : '' }}</textarea>
            @error('description')
                <p class="mt-1.5 text-[11px] text-red-500 dark:text-red-400 flex items-center gap-1">
                    <svg class="w-[11px] h-[11px] shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                            clip-rule="evenodd" />
                    </svg>
                    {{ $message }}
                </p>
            @enderror
        </div>
    </x-admin.form.ajout-modal>

    {{-- ══ MODAL — Modifier une catégorie ══ --}}
    <x-admin.form.modifier-modal id="modal-edit-categorie" title="Modifier la catégorie"
        subtitleId="edit-modal-subtitle" subtitle="—" action="" formId="edit-categorie-form" method="PUT"
        errorBag="edit" submitLabel="Enregistrer" submitColor="bg-pslb-blue-800 hover:bg-pslb-blue-700"
        maxWidth="max-w-[460px]">

        <div>
            <label class="block text-[11.5px] font-medium text-pslb-muted dark:text-pslb-blue-200 mb-1.5">
                Nom de la catégorie <span class="text-red-500">*</span>
            </label>
            <input type="text" name="nom" id="edit-name" value="{{ $fromEdit ? old('nom') : '' }}"
                class="w-full bg-pslb-surface dark:bg-pslb-blue-900 border rounded-lg py-[8px] px-3 text-[12.5px]
                       text-pslb-ink dark:text-pslb-blue-50 outline-none transition-colors placeholder:text-pslb-muted
                       @error('nom', 'edit') border-red-400 dark:border-red-500 focus:border-red-500
                       @else border-pslb-border dark:border-white/[.08] focus:border-pslb-cyan-500 @enderror"
                required />
            @error('nom', 'edit')
                <p class="mt-1.5 text-[11px] text-red-500 dark:text-red-400 flex items-center gap-1">
                    <svg class="w-[11px] h-[11px] shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                            clip-rule="evenodd" />
                    </svg>
                    {{ $message }}
                </p>
            @enderror
        </div>

        <div>
            <label class="block text-[11.5px] font-medium text-pslb-muted dark:text-pslb-blue-200 mb-1.5">
                Description
            </label>
            <textarea name="description" id="edit-description" rows="2"
                class="w-full bg-pslb-surface dark:bg-pslb-blue-900 border rounded-lg py-[8px] px-3 text-[12.5px]
                       text-pslb-ink dark:text-pslb-blue-50 outline-none transition-colors resize-none
                       @error('description', 'edit') border-red-400 dark:border-red-500 focus:border-red-500
                       @else border-pslb-border dark:border-white/[.08] focus:border-pslb-cyan-500 @enderror">{{ $fromEdit ? old('description') : '' }}</textarea>
            @error('description', 'edit')
                <p class="mt-1.5 text-[11px] text-red-500 dark:text-red-400 flex items-center gap-1">
                    <svg class="w-[11px] h-[11px] shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                            clip-rule="evenodd" />
                    </svg>
                    {{ $message }}
                </p>
            @enderror
        </div>
    </x-admin.form.modifier-modal>

    {{-- ══ Modal — Voir une catégorie ══ --}}
    <x-admin.form.voir-modal id="modal-view-categorie" subtitle="Détails de la catégorie" iconColor="violet"
        maxWidth="max-w-[460px]">
        <div>
            <p class="text-[11px] font-medium text-pslb-muted dark:text-pslb-blue-300 uppercase tracking-[.06em] mb-1">
                Nom de la catégorie
            </p>
            <p id="view-nom" class="text-[13px] font-medium text-pslb-ink dark:text-pslb-blue-50 m-0">—</p>
        </div>
        <div class="border-t border-pslb-border dark:border-white/[.08]"></div>
        <div>
            <p class="text-[11px] font-medium text-pslb-muted dark:text-pslb-blue-300 uppercase tracking-[.06em] mb-1">
                Description
            </p>
            <p id="view-description"
                class="text-[12.5px] text-pslb-muted dark:text-pslb-blue-200 m-0 leading-relaxed">—</p>
        </div>
    </x-admin.form.voir-modal>

    {{-- ══ MODAL — Supprimer une catégorie ══ --}}
    <x-admin.form.supprimer-modal id="modal-delete-categorie" title="Supprimer la catégorie"
        subtitleId="delete-modal-subtitle"
        subtitle="Impossible si des véhicules sont rattachés à cette catégorie." action=""
        formId="delete-categorie-form" maxWidth="max-w-[420px]" />

    <script>
        document.addEventListener('turbo:load', function() {

            const dt = new DataTable({
                tableId: 'datatable-categories',
                tbodyId: 'table-tbody',
                rowClass: 'table-row',
                searchId: 'search-table',
                perPageId: 'filter-per-page-select',
                countId: 'result-count',
                pagFromId: 'pag-from',
                pagToId: 'pag-to',
                pagTotalId: 'pag-total',
                pagBtnsId: 'pagination-buttons',
                emptyId: 'empty-state',
            });

            // Tom select
            const filterPerPageSelectAdd = initFormationSelect('filter-per-page-select');

            window.openEditModal = (id, name, description) => {
                const modalId = 'modal-edit-categorie';
                const action = `/admin/categories-vehicule/${id}`;
                const form = document.getElementById('edit-categorie-form');

                form.action = action;
                document.getElementById('edit-name').value = name;
                document.getElementById('edit-description').value = description;
                document.getElementById('edit-modal-subtitle').textContent = name;

                document.getElementById(`${modalId}-form-action`).value = action;
                document.getElementById(`${modalId}-modal-subtitle`).value = name;

                openModal(modalId);
            };

            window.openDeleteModal = (id, name) => {
                const modalId = 'modal-delete-categorie';
                const action = `/admin/categories-vehicule/${id}`;
                const form = document.getElementById('delete-categorie-form');

                form.action = action;
                document.getElementById('delete-modal-subtitle').textContent = name;

                document.getElementById(`${modalId}-form-action`).value = action;
                document.getElementById(`${modalId}-modal-subtitle`).value = name;

                openModal(modalId);
            };

        }, {
            once: true
        });
    </script>
@endsection
