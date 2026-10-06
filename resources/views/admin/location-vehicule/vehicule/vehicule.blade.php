@extends('layouts.admin.app', ['title' => 'Véhicules'])

@section('content')
    <style>
        #filter-per-page-select+.ts-wrapper {
            min-width: 160px;
            max-width: 220px;
            width: auto;
        }
    </style>
    @php
        $fromAdd = old('_modal') === 'modal-add-vehicule';
        $fromEdit = old('_modal') === 'modal-edit-vehicule';

        $statutBadge = [
            'disponible' => 'bg-pslb-cyan-500/10 text-pslb-cyan-700 dark:text-pslb-cyan-300',
            'maintenance' => 'bg-amber-500/10 text-amber-700 dark:text-amber-300',
            'hors_service' => 'bg-red-500/10 text-red-600 dark:text-red-300',
        ];

        // Champs communs aux modals Ajouter / Modifier
        $fields = [
            [
                'name' => 'categorie_vehicule_id',
                'label' => 'Catégorie',
                'type' => 'select',
                'required' => true,
                'options' => $categories->pluck('nom', 'id')->all(),
            ],
            [
                'name' => 'statut',
                'label' => 'Statut',
                'type' => 'select',
                'required' => true,
                'options' => $statuts,
                'default' => 'disponible',
            ],
            ['name' => 'marque', 'label' => 'Marque', 'required' => true, 'placeholder' => 'Ex : Toyota'],
            ['name' => 'modele', 'label' => 'Modèle', 'required' => true, 'placeholder' => 'Ex : Corolla'],
            [
                'name' => 'immatriculation',
                'label' => 'Immatriculation',
                'required' => true,
                'placeholder' => 'Ex : AB 1234 RB',
            ],
            ['name' => 'couleur', 'label' => 'Couleur', 'placeholder' => 'Ex : Gris'],
            ['name' => 'annee', 'label' => 'Année', 'type' => 'number', 'min' => 1990, 'placeholder' => 'Ex : 2022'],
            [
                'name' => 'nombre_places',
                'label' => 'Nombre de places',
                'type' => 'number',
                'min' => 1,
                'default' => 5,
            ],
            ['name' => 'transmission', 'label' => 'Transmission', 'type' => 'select', 'options' => $transmissions],
            ['name' => 'carburant', 'label' => 'Carburant', 'type' => 'select', 'options' => $carburants],
            [
                'name' => 'prix_jour',
                'label' => 'Prix par jour (FCFA)',
                'type' => 'number',
                'required' => true,
                'min' => 0,
                'step' => 500,
                'full' => true,
            ],
            [
                'name' => 'description',
                'label' => 'Description',
                'type' => 'textarea',
                'full' => true,
                'placeholder' => 'Options, état général, remarques…',
            ],
        ];
    @endphp

    {{-- ══ En-tête de page ══ --}}
    <x-admin.page-header title="Véhicules" description="Gestion du parc de véhicules" :count="$vehicules->count()"
        count-label="véhicules">
        <button onclick="openModal('modal-add-vehicule')" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-pslb-blue-800 hover:bg-pslb-blue-700
                       text-white text-[13px] font-medium cursor-pointer transition-colors border-0">
            <svg class="w-[14px] h-[14px]" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path d="M12 4v16m8-8H4" />
            </svg>
            Ajouter un véhicule
        </button>
    </x-admin.page-header>

    {{-- ══ Barre de filtres ══ --}}
    <div class="bg-white dark:bg-pslb-blue-800 border border-pslb-border dark:border-white/[.08]
                rounded-xl shadow-sm p-3.5 mb-4 flex flex-wrap gap-2.5 items-center">
        <x-admin.table.search />
        <x-admin.table.par-page-select />
        <span id="result-count" class="ml-auto text-[11.5px] text-pslb-muted dark:text-pslb-blue-300 shrink-0">
            {{ $vehicules->count() }}
        </span>
    </div>

    {{-- ══ Tableau véhicules ══ --}}
    <x-admin.table.datatable :columns="[
            ['label' => 'VÉHICULE', 'sort' => 'name'],
            ['label' => 'CATÉGORIE'],
            ['label' => 'PRIX / JOUR'],
            ['label' => 'STATUT'],
            ['label' => 'ACTIONS'],
        ]" tableId="datatable-vehicules" item-label="véhicules">
        @foreach ($vehicules as $vehicule)
            @php
                $nomComplet = trim($vehicule->marque . ' ' . $vehicule->modele);
                $prix = number_format($vehicule->prix_jour, 0, ',', ' ') . ' FCFA';
                $caracteristiques = collect([
                    $vehicule->annee,
                    $vehicule->couleur,
                    $vehicule->nombre_places ? $vehicule->nombre_places . ' places' : null,
                    $transmissions[$vehicule->transmission] ?? null,
                    $carburants[$vehicule->carburant] ?? null,
                ])
                    ->filter()
                    ->implode(' · ');
                $editData = [
                    'id' => $vehicule->id,
                    'categorie_vehicule_id' => $vehicule->categorie_vehicule_id,
                    'marque' => $vehicule->marque,
                    'modele' => $vehicule->modele,
                    'immatriculation' => $vehicule->immatriculation,
                    'annee' => $vehicule->annee,
                    'couleur' => $vehicule->couleur,
                    'nombre_places' => $vehicule->nombre_places,
                    'transmission' => $vehicule->transmission,
                    'carburant' => $vehicule->carburant,
                    'prix_jour' => $vehicule->prix_jour,
                    'statut' => $vehicule->statut,
                    'description' => $vehicule->description,
                ];
            @endphp
            <tr class="tr-hov border-b border-pslb-border dark:border-white/[.08] table-row"
                data-name="{{ $nomComplet }} {{ $vehicule->immatriculation }}">
                <td class="px-4 py-[11px]">
                    <div class="flex items-center gap-[10px]">
                        <div
                            class="w-[32px] h-[32px] rounded-full bg-pslb-cyan-500/15
                                            flex items-center justify-center
                                            text-pslb-cyan-700 dark:text-pslb-cyan-300 font-display text-[10px] font-bold shrink-0">
                            {{ getInitialesStartEnd($nomComplet) }}
                        </div>
                        <div>
                            <p class="font-medium text-[12.5px] text-pslb-ink dark:text-pslb-blue-50 m-0">
                                {{ $nomComplet }}
                                <span class="text-pslb-muted dark:text-pslb-blue-300 font-normal">·
                                    {{ $vehicule->immatriculation }}</span>
                            </p>
                            <p class="text-[10px] text-pslb-muted dark:text-pslb-blue-300 m-0">
                                {{ $caracteristiques ?: '—' }}
                            </p>
                        </div>
                    </div>
                </td>
                <td class="px-3 py-[11px] text-pslb-muted dark:text-pslb-blue-200 hidden sm:table-cell">
                    {{ $vehicule->categorie->nom ?? '—' }}
                </td>
                <td class="px-3 py-[11px] text-[12.5px] font-medium text-pslb-ink dark:text-pslb-blue-50 whitespace-nowrap">
                    {{ $prix }}
                </td>
                <td class="px-3 py-[11px]">
                    <span
                        class="inline-block px-2.5 py-0.5 rounded-full text-[11px] font-medium {{ $statutBadge[$vehicule->statut] ?? '' }}">
                        {{ $statuts[$vehicule->statut] ?? $vehicule->statut }}
                    </span>
                </td>
                <td class="px-4 py-[11px] text-right">
                    <div class="flex items-center justify-end gap-1.5">
                        <x-admin.ux.button.modifier-modal onclick="openEditModal({{ Js::from($editData) }})" />
                        <x-admin.ux.button.voir-modal modal="modal-view-vehicule" subtitle="{{ $nomComplet }}"
                            tip="Voir le véhicule" :fields="[
                    'vehicule' => $nomComplet,
                    'categorie' => $vehicule->categorie->nom ?? '—',
                    'immatriculation' => $vehicule->immatriculation,
                    'caracteristiques' => $caracteristiques ?: '—',
                    'prix_jour' => $prix,
                    'statut' => $statuts[$vehicule->statut] ?? $vehicule->statut,
                    'description' => $vehicule->description ?: '—',
                ]" />
                        <x-admin.ux.button.supprimer-modal
                            onclick="openDeleteModal({{ $vehicule->id }}, {{ Js::from($nomComplet) }})" />
                    </div>
                </td>
            </tr>
        @endforeach
    </x-admin.table.datatable>

    {{-- ══ MODAL — Ajouter un véhicule ══ --}}
    <x-admin.form.ajout-modal id="modal-add-vehicule" title="Ajouter un véhicule"
        subtitle="Renseignez les informations du véhicule" :action="route('admin.vehicules.store')"
        submitLabel="Ajouter le véhicule" maxWidth="max-w-[640px]">
        @include('admin.partials.form-fields', [
            'fields' => $fields,
            'mode' => 'add',
            'bag' => 'default',
            'from' => $fromAdd,
        ])
    </x-admin.form.ajout-modal>

    {{-- ══ MODAL — Modifier un véhicule ══ --}}
    <x-admin.form.modifier-modal id="modal-edit-vehicule" title="Modifier le véhicule" subtitleId="edit-modal-subtitle"
        subtitle="—" action="" formId="edit-vehicule-form" method="PUT" errorBag="edit" submitLabel="Enregistrer"
        submitColor="bg-pslb-blue-800 hover:bg-pslb-blue-700" maxWidth="max-w-[640px]">
        @include('admin.partials.form-fields', [
            'fields' => $fields,
            'mode' => 'edit',
            'bag' => 'edit',
            'from' => $fromEdit,
        ])
    </x-admin.form.modifier-modal>

    {{-- ══ Modal — Voir un véhicule ══ --}}
    <x-admin.form.voir-modal id="modal-view-vehicule" subtitle="Détails du véhicule" iconColor="violet"
        maxWidth="max-w-[500px]">
        @foreach ([
                'vehicule' => 'Véhicule',
                'categorie' => 'Catégorie',
                'immatriculation' => 'Immatriculation',
                'caracteristiques' => 'Caractéristiques',
                'prix_jour' => 'Prix par jour',
                'statut' => 'Statut',
                'description' => 'Description',
            ] as $key => $label)
            <div>
                <p class="text-[11px] font-medium text-pslb-muted dark:text-pslb-blue-300 uppercase tracking-[.06em] mb-1">
                    {{ $label }}
                </p>
                <p id="view-{{ $key }}" class="text-[12.5px] text-pslb-ink dark:text-pslb-blue-50 m-0 leading-relaxed">—</p>
            </div>
            @if (!$loop->last)
                <div class="border-t border-pslb-border dark:border-white/[.08]"></div>
            @endif
        @endforeach
    </x-admin.form.voir-modal>

    {{-- ══ MODAL — Supprimer un véhicule ══ --}}
    <x-admin.form.supprimer-modal id="modal-delete-vehicule" title="Supprimer le véhicule"
        subtitleId="delete-modal-subtitle" subtitle="Impossible si le véhicule a des réservations." action=""
        formId="delete-vehicule-form" maxWidth="max-w-[420px]" />

    <script>
        document.addEventListener('turbo:load', function () {

            const dt = new DataTable({
                tableId: 'datatable-vehicules',
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

            window.openEditModal = (data) => {
                const modalId = 'modal-edit-vehicule';
                const action = `/admin/vehicules/${data.id}`;
                const label = `${data.marque} ${data.modele}`;

                document.getElementById('edit-vehicule-form').action = action;

                // Remplit chaque champ dont l'id est edit-{nom}
                Object.entries(data).forEach(([key, value]) => {
                    const el = document.getElementById(`edit-${key}`);
                    if (el) el.value = value ?? '';
                });

                document.getElementById('edit-modal-subtitle').textContent = label;
                document.getElementById(`${modalId}-form-action`).value = action;
                document.getElementById(`${modalId}-modal-subtitle`).value = label;

                openModal(modalId);
            };

            window.openDeleteModal = (id, name) => {
                const modalId = 'modal-delete-vehicule';
                const action = `/admin/vehicules/${id}`;

                document.getElementById('delete-vehicule-form').action = action;
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
