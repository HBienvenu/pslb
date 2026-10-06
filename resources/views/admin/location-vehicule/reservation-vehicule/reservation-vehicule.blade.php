@extends('layouts.admin.app', ['title' => 'Réservations'])

@section('content')
    <style>
        #filter-per-page-select+.ts-wrapper {
            min-width: 160px;
            max-width: 220px;
            width: auto;
        }
    </style>
    @php
        $fromAdd = old('_modal') === 'modal-add-reservation';
        $fromEdit = old('_modal') === 'modal-edit-reservation';

        $statutBadge = [
            'en_attente' => 'bg-amber-500/10 text-amber-700 dark:text-amber-300',
            'confirmee' => 'bg-pslb-cyan-500/10 text-pslb-cyan-700 dark:text-pslb-cyan-300',
            'en_cours' => 'bg-pslb-blue-500/10 text-pslb-blue-700 dark:text-pslb-blue-200',
            'terminee' => 'bg-pslb-muted/10 text-pslb-muted dark:text-pslb-blue-300',
            'annulee' => 'bg-red-500/10 text-red-600 dark:text-red-300',
        ];

        $vehiculeOptions = $vehicules
            ->mapWithKeys(
                fn($v) => [
                    $v->id =>
                        "{$v->marque} {$v->modele} — {$v->immatriculation}" .
                        ($v->statut !== 'disponible' ? ' (indisponible)' : ''),
                ],
            )
            ->all();

        // Champs communs aux modals Ajouter / Modifier
        $fields = [
            [
                'name' => 'vehicule_id',
                'label' => 'Véhicule',
                'type' => 'select',
                'required' => true,
                'options' => $vehiculeOptions,
                'full' => true,
            ],
            [
                'name' => 'nom_client',
                'label' => 'Nom du client',
                'required' => true,
                'placeholder' => 'Ex : Koffi Adjovi',
            ],
            [
                'name' => 'telephone',
                'label' => 'Téléphone',
                'required' => true,
                'placeholder' => 'Ex : +229 01 97 00 00 00',
            ],
            [
                'name' => 'email',
                'label' => 'E-mail',
                'type' => 'email',
                'placeholder' => 'client@exemple.com',
                'full' => true,
            ],
            ['name' => 'date_debut', 'label' => 'Date de début', 'type' => 'date', 'required' => true],
            ['name' => 'date_fin', 'label' => 'Date de fin', 'type' => 'date', 'required' => true],
            [
                'name' => 'statut',
                'label' => 'Statut',
                'type' => 'select',
                'required' => true,
                'options' => $statuts,
                'default' => 'en_attente',
                'full' => true,
            ],
            [
                'name' => 'notes',
                'label' => 'Notes',
                'type' => 'textarea',
                'full' => true,
                'placeholder' => 'Lieu de remise, caution, demandes particulières…',
            ],
        ];

        $tabBase = 'inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-[12px] font-medium no-underline transition-colors border';
        $tabOn = 'bg-pslb-blue-800 text-white border-pslb-blue-800';
        $tabOff =
            'bg-white dark:bg-pslb-blue-800 text-pslb-muted dark:text-pslb-blue-200 border-pslb-border dark:border-white/[.08] hover:text-pslb-blue-800 dark:hover:text-white';
    @endphp

    {{-- ══ En-tête de page ══ --}}
    <x-admin.page-header title="Réservations" description="Gestion des réservations de véhicules"
        :count="$reservations->count()" count-label="réservations">
        <button onclick="openModal('modal-add-reservation')" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-pslb-blue-800 hover:bg-pslb-blue-700
                       text-white text-[13px] font-medium cursor-pointer transition-colors border-0">
            <svg class="w-[14px] h-[14px]" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path d="M12 4v16m8-8H4" />
            </svg>
            Nouvelle réservation
        </button>
    </x-admin.page-header>

    {{-- ══ Onglets de statut ══ --}}
    <div class="flex flex-wrap gap-1.5 mb-3">
        <a href="{{ route('admin.reservations.index') }}" class="{{ $tabBase }} {{ !$filtre ? $tabOn : $tabOff }}">
            Toutes <span class="opacity-70">{{ $counts->sum() }}</span>
        </a>
        @foreach ($statuts as $value => $label)
            <a href="{{ route('admin.reservations.index', ['statut' => $value]) }}"
                class="{{ $tabBase }} {{ $filtre === $value ? $tabOn : $tabOff }}">
                {{ $label }} <span class="opacity-70">{{ $counts[$value] ?? 0 }}</span>
            </a>
        @endforeach
    </div>

    {{-- ══ Barre de filtres ══ --}}
    <div class="bg-white dark:bg-pslb-blue-800 border border-pslb-border dark:border-white/[.08]
                rounded-xl shadow-sm p-3.5 mb-4 flex flex-wrap gap-2.5 items-center">
        <x-admin.table.search />
        <x-admin.table.par-page-select />
        <span id="result-count" class="ml-auto text-[11.5px] text-pslb-muted dark:text-pslb-blue-300 shrink-0">
            {{ $reservations->count() }}
        </span>
    </div>

    {{-- ══ Tableau réservations ══ --}}
    <x-admin.table.datatable :columns="[
            ['label' => 'CLIENT', 'sort' => 'name'],
            ['label' => 'VÉHICULE'],
            ['label' => 'PÉRIODE'],
            ['label' => 'TOTAL'],
            ['label' => 'STATUT'],
            ['label' => 'ACTIONS'],
        ]" tableId="datatable-reservations"
        item-label="réservations">
        @foreach ($reservations as $reservation)
            @php
                $debut = \Carbon\Carbon::parse($reservation->date_debut);
                $fin = \Carbon\Carbon::parse($reservation->date_fin);
                $jours = (int) $debut->diffInDays($fin) + 1;
                $periode = $debut->format('d/m/Y') . ' → ' . $fin->format('d/m/Y');
                $total = number_format($reservation->prix_total, 0, ',', ' ') . ' FCFA';
                $vehiculeNom = $reservation->vehicule
                    ? trim($reservation->vehicule->marque . ' ' . $reservation->vehicule->modele)
                    : '—';
                $editData = [
                    'id' => $reservation->id,
                    'vehicule_id' => $reservation->vehicule_id,
                    'nom_client' => $reservation->nom_client,
                    'telephone' => $reservation->telephone,
                    'email' => $reservation->email,
                    'date_debut' => $debut->format('Y-m-d'),
                    'date_fin' => $fin->format('Y-m-d'),
                    'statut' => $reservation->statut,
                    'notes' => $reservation->notes,
                ];
            @endphp
            <tr class="tr-hov border-b border-pslb-border dark:border-white/[.08] table-row"
                data-name="{{ $reservation->nom_client }} {{ $vehiculeNom }}">
                <td class="px-4 py-[11px]">
                    <div class="flex items-center gap-[10px]">
                        <div
                            class="w-[32px] h-[32px] rounded-full bg-pslb-cyan-500/15
                                            flex items-center justify-center
                                            text-pslb-cyan-700 dark:text-pslb-cyan-300 font-display text-[10px] font-bold shrink-0">
                            {{ getInitialesStartEnd($reservation->nom_client) }}
                        </div>
                        <div>
                            <p class="font-medium text-[12.5px] text-pslb-ink dark:text-pslb-blue-50 m-0">
                                {{ $reservation->nom_client }}
                            </p>
                            <p class="text-[10px] text-pslb-muted dark:text-pslb-blue-300 m-0">
                                {{ $reservation->telephone }}
                            </p>
                        </div>
                    </div>
                </td>
                <td class="px-3 py-[11px] text-pslb-muted dark:text-pslb-blue-200 hidden sm:table-cell">
                    {{ $vehiculeNom }}
                    <span class="block text-[10px] text-pslb-muted dark:text-pslb-blue-300">
                        {{ $reservation->vehicule->immatriculation ?? '' }}
                    </span>
                </td>
                <td class="px-3 py-[11px] text-[12px] text-pslb-ink dark:text-pslb-blue-50 whitespace-nowrap">
                    {{ $periode }}
                    <span class="block text-[10px] text-pslb-muted dark:text-pslb-blue-300">
                        {{ $jours }} {{ $jours > 1 ? 'jours' : 'jour' }}
                    </span>
                </td>
                <td class="px-3 py-[11px] text-[12.5px] font-medium text-pslb-ink dark:text-pslb-blue-50 whitespace-nowrap">
                    {{ $total }}
                </td>
                <td class="px-3 py-[11px]">
                    {{-- Changement rapide de statut --}}
                    <form method="POST" action="{{ route('admin.reservations.statut', $reservation) }}" data-turbo="false">
                        @csrf
                        @method('PATCH')
                        <select name="statut" onchange="this.form.submit()"
                            class="px-2.5 py-1 rounded-full border-0 outline-none cursor-pointer text-[11px] font-medium {{ $statutBadge[$reservation->statut] ?? '' }}">
                            @foreach ($statuts as $value => $label)
                                <option value="{{ $value }}" @selected($reservation->statut === $value)
                                    class="bg-white text-pslb-ink">{{ $label }}</option>
                            @endforeach
                        </select>
                    </form>
                </td>
                <td class="px-4 py-[11px] text-right">
                    <div class="flex items-center justify-end gap-1.5">
                        <x-admin.ux.button.modifier-modal onclick="openEditModal({{ Js::from($editData) }})" />
                        <x-admin.ux.button.voir-modal modal="modal-view-reservation" subtitle="{{ $reservation->nom_client }}"
                            tip="Voir la réservation" :fields="[
                    'nom_client' => $reservation->nom_client,
                    'telephone' => $reservation->telephone,
                    'email' => $reservation->email ?: '—',
                    'vehicule' => $vehiculeNom . ' · ' . ($reservation->vehicule->immatriculation ?? ''),
                    'periode' => $periode . ' (' . $jours . ' j)',
                    'total' => $total,
                    'statut' => $statuts[$reservation->statut] ?? $reservation->statut,
                    'notes' => $reservation->notes ?: '—',
                ]" />
                        <x-admin.ux.button.supprimer-modal
                            onclick="openDeleteModal({{ $reservation->id }}, {{ Js::from($reservation->nom_client) }})" />
                    </div>
                </td>
            </tr>
        @endforeach
    </x-admin.table.datatable>

    {{-- ══ MODAL — Nouvelle réservation ══ --}}
    <x-admin.form.ajout-modal id="modal-add-reservation" title="Nouvelle réservation"
        subtitle="Le prix total est calculé automatiquement" :action="route('admin.reservations.store')"
        submitLabel="Créer la réservation" maxWidth="max-w-[600px]">
        @include('admin.partials.form-fields', [
            'fields' => $fields,
            'mode' => 'add',
            'bag' => 'default',
            'from' => $fromAdd,
        ])
    </x-admin.form.ajout-modal>

    {{-- ══ MODAL — Modifier une réservation ══ --}}
    <x-admin.form.modifier-modal id="modal-edit-reservation" title="Modifier la réservation"
        subtitleId="edit-modal-subtitle" subtitle="—" action="" formId="edit-reservation-form" method="PUT" errorBag="edit"
        submitLabel="Enregistrer" submitColor="bg-pslb-blue-800 hover:bg-pslb-blue-700" maxWidth="max-w-[600px]">
        @include('admin.partials.form-fields', [
            'fields' => $fields,
            'mode' => 'edit',
            'bag' => 'edit',
            'from' => $fromEdit,
        ])
    </x-admin.form.modifier-modal>

    {{-- ══ Modal — Voir une réservation ══ --}}
    <x-admin.form.voir-modal id="modal-view-reservation" subtitle="Détails de la réservation" iconColor="violet"
        maxWidth="max-w-[500px]">
        @foreach ([
                'nom_client' => 'Client',
                'telephone' => 'Téléphone',
                'email' => 'E-mail',
                'vehicule' => 'Véhicule',
                'periode' => 'Période',
                'total' => 'Total',
                'statut' => 'Statut',
                'notes' => 'Notes',
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

    {{-- ══ MODAL — Supprimer une réservation ══ --}}
    <x-admin.form.supprimer-modal id="modal-delete-reservation" title="Supprimer la réservation"
        subtitleId="delete-modal-subtitle" subtitle="Une réservation en cours ne peut pas être supprimée." action=""
        formId="delete-reservation-form" maxWidth="max-w-[420px]" />

    <script>
        document.addEventListener('turbo:load', function () {

            const dt = new DataTable({
                tableId: 'datatable-reservations',
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
                const modalId = 'modal-edit-reservation';
                const action = `/admin/reservations/${data.id}`;

                document.getElementById('edit-reservation-form').action = action;

                // Remplit chaque champ dont l'id est edit-{nom}
                Object.entries(data).forEach(([key, value]) => {
                    const el = document.getElementById(`edit-${key}`);
                    if (el) el.value = value ?? '';
                });

                document.getElementById('edit-modal-subtitle').textContent = data.nom_client;
                document.getElementById(`${modalId}-form-action`).value = action;
                document.getElementById(`${modalId}-modal-subtitle`).value = data.nom_client;

                openModal(modalId);
            };

            window.openDeleteModal = (id, name) => {
                const modalId = 'modal-delete-reservation';
                const action = `/admin/reservations/${id}`;

                document.getElementById('delete-reservation-form').action = action;
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
