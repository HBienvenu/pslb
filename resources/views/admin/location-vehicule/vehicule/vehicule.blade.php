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

        // ── Helpers de style (évitent de répéter les classes sur chaque champ) ──
        $lbl = 'block text-[11.5px] font-medium text-pslb-muted dark:text-pslb-blue-200 mb-1.5';
        $base = 'w-full bg-pslb-surface dark:bg-pslb-blue-900 border rounded-lg py-[8px] px-3 text-[12.5px] text-pslb-ink dark:text-pslb-blue-50 outline-none transition-colors placeholder:text-pslb-muted';
        $cls = fn($name, $bag = 'default') => $base . ' ' . ($errors->getBag($bag)->has($name)
            ? 'border-red-400 dark:border-red-500 focus:border-red-500'
            : 'border-pslb-border dark:border-white/[.08] focus:border-pslb-cyan-500');
        $fileCls = fn($name, $bag = 'default') => 'w-full bg-pslb-surface dark:bg-pslb-blue-900 border rounded-lg p-1.5 text-[12px] text-pslb-muted dark:text-pslb-blue-200 cursor-pointer file:mr-3 file:px-3 file:py-[6px] file:rounded-md file:border-0 file:bg-pslb-blue-50 file:text-pslb-blue-800 file:text-[12px] file:font-medium file:cursor-pointer hover:file:bg-pslb-blue-100 dark:file:bg-pslb-blue-700 dark:file:text-pslb-blue-50 ' . ($errors->getBag($bag)->has($name)
            ? 'border-red-400 dark:border-red-500'
            : 'border-pslb-border dark:border-white/[.08]');
        $err = fn($name, $bag = 'default') => $errors->getBag($bag)->has($name)
            ? '<p class="mt-1.5 text-[11px] text-red-500 dark:text-red-400">' . e($errors->getBag($bag)->first($name)) . '</p>'
            : '';
        $toggle = fn($name, $id, $title, $hint, $checked) => '<div class="flex items-center justify-between p-3 rounded-lg bg-pslb-surface dark:bg-pslb-blue-900 border border-pslb-border dark:border-white/[.08]">'
            . '<div><p class="text-[12px] font-medium text-pslb-ink dark:text-pslb-blue-100 m-0">' . $title . '</p>'
            . '<p class="text-[10.5px] text-pslb-muted dark:text-pslb-blue-300 mt-[1px] m-0">' . $hint . '</p></div>'
            . '<label class="relative inline-flex items-center cursor-pointer ml-4 shrink-0">'
            . '<input type="checkbox" name="' . $name . '" id="' . $id . '" value="1" class="sr-only peer"' . ($checked ? ' checked' : '') . '>'
            . '<div class="w-9 h-5 bg-gray-200 dark:bg-pslb-blue-700 rounded-full peer-checked:bg-pslb-cyan-500 transition-colors"></div>'
            . '<div class="absolute left-[2px] top-[2px] w-4 h-4 bg-white rounded-full peer-checked:translate-x-4 transition-transform shadow-sm"></div>'
            . '</label></div>';
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
            ['label' => 'SITE'],
            ['label' => 'ACTIONS'],
        ]" tableId="datatable-vehicules" item-label="véhicules">
        @foreach ($vehicules as $vehicule)
            @php
                $prix = $vehicule->prix_jour ? number_format($vehicule->prix_jour, 0, ',', ' ') . ' FCFA' : 'Sur devis';
                $caracteristiques = collect([
                    $vehicule->nombre_places ? $vehicule->nombre_places . ' places' : null,
                    $vehicule->boite_vitesse,
                    $vehicule->carburant,
                    $vehicule->climatisation ? 'Climatisé' : null,
                ])
                    ->filter()
                    ->implode(' · ');
                $editData = [
                    'id' => $vehicule->id,
                    'categorie_id' => $vehicule->categorie_id,
                    'nom' => $vehicule->nom,
                    'immatriculation' => $vehicule->immatriculation,
                    'description' => $vehicule->description,
                    'nombre_places' => $vehicule->nombre_places,
                    'boite_vitesse' => $vehicule->boite_vitesse,
                    'carburant' => $vehicule->carburant,
                    'prix_jour' => $vehicule->prix_jour,
                    'ordre' => $vehicule->ordre,
                    'climatisation' => (bool) $vehicule->climatisation,
                    'est_disponible' => (bool) $vehicule->est_disponible,
                ];
            @endphp
            <tr class="tr-hov border-b border-pslb-border dark:border-white/[.08] table-row"
                data-name="{{ $vehicule->nom }} {{ $vehicule->immatriculation }}">
                <td class="px-4 py-[11px]">
                    <div class="flex items-center gap-[10px]">
                        @if ($vehicule->image)
                            <img src="{{ asset('storage/' . $vehicule->image) }}" alt="{{ $vehicule->nom }}" loading="lazy"
                                class="w-[40px] h-[32px] rounded-md object-cover shrink-0" />
                        @else
                            <div
                                class="w-[40px] h-[32px] rounded-md bg-pslb-cyan-500/15
                                                                flex items-center justify-center
                                                                text-pslb-cyan-700 dark:text-pslb-cyan-300 font-display text-[10px] font-bold shrink-0">
                                {{ getInitialesStartEnd($vehicule->nom) }}
                            </div>
                        @endif
                        <div>
                            <p class="font-medium text-[12.5px] text-pslb-ink dark:text-pslb-blue-50 m-0">
                                {{ $vehicule->nom }}
                                @if ($vehicule->immatriculation)
                                    <span class="text-pslb-muted dark:text-pslb-blue-300 font-normal">·
                                        {{ $vehicule->immatriculation }}</span>
                                @endif
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
                    @if ($vehicule->est_disponible)
                        <span
                            class="inline-block px-2.5 py-0.5 rounded-full text-[11px] font-medium bg-pslb-cyan-500/10 text-pslb-cyan-700 dark:text-pslb-cyan-300">En
                            ligne</span>
                    @else
                        <span
                            class="inline-block px-2.5 py-0.5 rounded-full text-[11px] font-medium bg-pslb-muted/10 text-pslb-muted dark:text-pslb-blue-300">Masqué</span>
                    @endif
                </td>
                <td class="px-4 py-[11px] text-right">
                    <div class="flex items-center justify-end gap-1.5">
                        <x-admin.ux.button.modifier-modal onclick="openEditModal({{ Js::from($editData) }})" />
                        <x-admin.ux.button.voir-modal modal="modal-view-vehicule" subtitle="{{ $vehicule->nom }}"
                            tip="Voir le véhicule" :fields="[
                    'nom' => $vehicule->nom,
                    'categorie' => $vehicule->categorie->nom ?? '—',
                    'immatriculation' => $vehicule->immatriculation ?: '—',
                    'caracteristiques' => $caracteristiques ?: '—',
                    'prix_jour' => $prix,
                    'visibilite' => $vehicule->est_disponible ? 'Visible sur le site' : 'Masqué du site',
                    'description' => $vehicule->description ?: '—',
                ]" />
                        <x-admin.ux.button.supprimer-modal
                            onclick="openDeleteModal({{ $vehicule->id }}, {{ Js::from($vehicule->nom) }})" />
                    </div>
                </td>
            </tr>
        @endforeach
    </x-admin.table.datatable>

    {{-- ══ MODAL — Ajouter un véhicule ══ --}}
    <x-admin.form.ajout-modal id="modal-add-vehicule" title="Ajouter un véhicule"
        subtitle="Renseignez les informations du véhicule" :action="route('admin.vehicules.store')"
        submitLabel="Ajouter le véhicule" maxWidth="max-w-[720px]" enctype="multipart/form-data">

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">

            {{-- Nom --}}
            <div class="sm:col-span-2">
                <label class="{{ $lbl }}">Nom du véhicule <span class="text-red-500">*</span></label>
                <input type="text" name="nom" value="{{ $fromAdd ? old('nom') : '' }}"
                    placeholder="Ex : Toyota Corolla 2022" class="{{ $cls('nom') }}" required />
                {!! $err('nom') !!}
            </div>

            {{-- Catégorie --}}
            <div>
                <label class="{{ $lbl }}">Catégorie</label>
                <select name="categorie_id" class="{{ $cls('categorie_id') }} cursor-pointer">
                    <option value="">— Aucune —</option>
                    @foreach ($categories as $categorie)
                        <option value="{{ $categorie->id }}" @selected($fromAdd && old('categorie_id') == $categorie->id)>
                            {{ $categorie->nom }}
                        </option>
                    @endforeach
                </select>
                {!! $err('categorie_id') !!}
            </div>

            {{-- Immatriculation --}}
            <div>
                <label class="{{ $lbl }}">Immatriculation</label>
                <input type="text" name="immatriculation" value="{{ $fromAdd ? old('immatriculation') : '' }}"
                    placeholder="Ex : AB 1234 RB" class="{{ $cls('immatriculation') }}" />
                {!! $err('immatriculation') !!}
            </div>

            {{-- Prix par jour --}}
            <div>
                <label class="{{ $lbl }}">Prix par jour <span class="font-normal text-pslb-muted ml-1">(FCFA)</span></label>
                <input type="number" name="prix_jour" value="{{ $fromAdd ? old('prix_jour') : '' }}" min="0" step="500"
                    placeholder="Ex : 35000" class="{{ $cls('prix_jour') }}" />
                {!! $err('prix_jour') !!}
            </div>

            {{-- Nombre de places --}}
            <div>
                <label class="{{ $lbl }}">Nombre de places</label>
                <input type="number" name="nombre_places" value="{{ $fromAdd ? old('nombre_places') : '' }}" min="1"
                    placeholder="Ex : 5" class="{{ $cls('nombre_places') }}" />
                {!! $err('nombre_places') !!}
            </div>

            {{-- Boîte de vitesse --}}
            <div>
                <label class="{{ $lbl }}">Boîte de vitesse</label>
                <select name="boite_vitesse" class="{{ $cls('boite_vitesse') }} cursor-pointer">
                    <option value="">— Choisir —</option>
                    @foreach ($boites as $boite)
                        <option value="{{ $boite }}" @selected($fromAdd && old('boite_vitesse') === $boite)>{{ $boite }}
                        </option>
                    @endforeach
                </select>
                {!! $err('boite_vitesse') !!}
            </div>

            {{-- Carburant --}}
            <div>
                <label class="{{ $lbl }}">Carburant</label>
                <select name="carburant" class="{{ $cls('carburant') }} cursor-pointer">
                    <option value="">— Choisir —</option>
                    @foreach ($carburants as $carburant)
                        <option value="{{ $carburant }}" @selected($fromAdd && old('carburant') === $carburant)>{{ $carburant }}
                        </option>
                    @endforeach
                </select>
                {!! $err('carburant') !!}
            </div>

            {{-- Ordre d'affichage --}}
            <div class="sm:col-span-2">
                <label class="{{ $lbl }}">Ordre d'affichage <span class="font-normal text-pslb-muted ml-1">(0 = en
                        premier)</span></label>
                <input type="number" name="ordre" value="{{ $fromAdd ? old('ordre', 0) : 0 }}" min="0"
                    class="{{ $cls('ordre') }}" />
                {!! $err('ordre') !!}
            </div>

            {{-- Description --}}
            <div class="sm:col-span-2">
                <label class="{{ $lbl }}">Description</label>
                <textarea name="description" rows="2" placeholder="Options, état général, remarques…"
                    class="{{ $cls('description') }} resize-none">{{ $fromAdd ? old('description') : '' }}</textarea>
                {!! $err('description') !!}
            </div>

            {{-- Image --}}
            <div class="sm:col-span-2">
                <label class="{{ $lbl }}">Photo du véhicule <span class="font-normal text-pslb-muted ml-1">(JPG, PNG ou WebP
                        · 3 Mo max)</span></label>
                <input type="file" name="image" accept="image/png,image/jpeg,image/webp" class="{{ $fileCls('image') }}" />
                {!! $err('image') !!}
            </div>

            {{-- Climatisation --}}
            {!! $toggle('climatisation', 'add-climatisation', 'Climatisation', 'Véhicule climatisé', !$fromAdd || old('climatisation')) !!}

            {{-- Visible --}}
            {!! $toggle('est_disponible', 'add-est_disponible', 'Visible sur le site', 'Proposé aux visiteurs', !$fromAdd || old('est_disponible')) !!}

        </div>
    </x-admin.form.ajout-modal>

    {{-- ══ MODAL — Modifier un véhicule ══ --}}
    <x-admin.form.modifier-modal id="modal-edit-vehicule" title="Modifier le véhicule" subtitleId="edit-modal-subtitle"
        subtitle="—" action="" formId="edit-vehicule-form" method="PUT" errorBag="edit" submitLabel="Enregistrer"
        submitColor="bg-pslb-blue-800 hover:bg-pslb-blue-700" maxWidth="max-w-[720px]" enctype="multipart/form-data">

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">

            {{-- Nom --}}
            <div class="sm:col-span-2">
                <label class="{{ $lbl }}">Nom du véhicule <span class="text-red-500">*</span></label>
                <input type="text" name="nom" id="edit-nom" value="{{ $fromEdit ? old('nom') : '' }}"
                    class="{{ $cls('nom', 'edit') }}" required />
                {!! $err('nom', 'edit') !!}
            </div>

            {{-- Catégorie --}}
            <div>
                <label class="{{ $lbl }}">Catégorie</label>
                <select name="categorie_id" id="edit-categorie_id"
                    class="{{ $cls('categorie_id', 'edit') }} cursor-pointer">
                    <option value="">— Aucune —</option>
                    @foreach ($categories as $categorie)
                        <option value="{{ $categorie->id }}" @selected($fromEdit && old('categorie_id') == $categorie->id)>
                            {{ $categorie->nom }}
                        </option>
                    @endforeach
                </select>
                {!! $err('categorie_id', 'edit') !!}
            </div>

            {{-- Immatriculation --}}
            <div>
                <label class="{{ $lbl }}">Immatriculation</label>
                <input type="text" name="immatriculation" id="edit-immatriculation"
                    value="{{ $fromEdit ? old('immatriculation') : '' }}" class="{{ $cls('immatriculation', 'edit') }}" />
                {!! $err('immatriculation', 'edit') !!}
            </div>

            {{-- Prix par jour --}}
            <div>
                <label class="{{ $lbl }}">Prix par jour <span class="font-normal text-pslb-muted ml-1">(FCFA)</span></label>
                <input type="number" name="prix_jour" id="edit-prix_jour" value="{{ $fromEdit ? old('prix_jour') : '' }}"
                    min="0" step="500" class="{{ $cls('prix_jour', 'edit') }}" />
                {!! $err('prix_jour', 'edit') !!}
            </div>

            {{-- Nombre de places --}}
            <div>
                <label class="{{ $lbl }}">Nombre de places</label>
                <input type="number" name="nombre_places" id="edit-nombre_places"
                    value="{{ $fromEdit ? old('nombre_places') : '' }}" min="1"
                    class="{{ $cls('nombre_places', 'edit') }}" />
                {!! $err('nombre_places', 'edit') !!}
            </div>

            {{-- Boîte de vitesse --}}
            <div>
                <label class="{{ $lbl }}">Boîte de vitesse</label>
                <select name="boite_vitesse" id="edit-boite_vitesse"
                    class="{{ $cls('boite_vitesse', 'edit') }} cursor-pointer">
                    <option value="">— Choisir —</option>
                    @foreach ($boites as $boite)
                        <option value="{{ $boite }}" @selected($fromEdit && old('boite_vitesse') === $boite)>{{ $boite }}
                        </option>
                    @endforeach
                </select>
                {!! $err('boite_vitesse', 'edit') !!}
            </div>

            {{-- Carburant --}}
            <div>
                <label class="{{ $lbl }}">Carburant</label>
                <select name="carburant" id="edit-carburant" class="{{ $cls('carburant', 'edit') }} cursor-pointer">
                    <option value="">— Choisir —</option>
                    @foreach ($carburants as $carburant)
                        <option value="{{ $carburant }}" @selected($fromEdit && old('carburant') === $carburant)>{{ $carburant }}
                        </option>
                    @endforeach
                </select>
                {!! $err('carburant', 'edit') !!}
            </div>

            {{-- Ordre d'affichage --}}
            <div class="sm:col-span-2">
                <label class="{{ $lbl }}">Ordre d'affichage <span class="font-normal text-pslb-muted ml-1">(0 = en
                        premier)</span></label>
                <input type="number" name="ordre" id="edit-ordre" value="{{ $fromEdit ? old('ordre') : '' }}" min="0"
                    class="{{ $cls('ordre', 'edit') }}" />
                {!! $err('ordre', 'edit') !!}
            </div>

            {{-- Description --}}
            <div class="sm:col-span-2">
                <label class="{{ $lbl }}">Description</label>
                <textarea name="description" id="edit-description" rows="2"
                    class="{{ $cls('description', 'edit') }} resize-none">{{ $fromEdit ? old('description') : '' }}</textarea>
                {!! $err('description', 'edit') !!}
            </div>

            {{-- Image --}}
            <div class="sm:col-span-2">
                <label class="{{ $lbl }}">Nouvelle photo <span class="font-normal text-pslb-muted ml-1">(laisser vide pour
                        conserver l'actuelle)</span></label>
                <input type="file" name="image" id="edit-image" accept="image/png,image/jpeg,image/webp"
                    class="{{ $fileCls('image', 'edit') }}" />
                {!! $err('image', 'edit') !!}
            </div>

            {{-- Climatisation --}}
            {!! $toggle('climatisation', 'edit-climatisation', 'Climatisation', 'Véhicule climatisé', $fromEdit && old('climatisation')) !!}

            {{-- Visible --}}
            {!! $toggle('est_disponible', 'edit-est_disponible', 'Visible sur le site', 'Proposé aux visiteurs', $fromEdit && old('est_disponible')) !!}

        </div>
    </x-admin.form.modifier-modal>

    {{-- ══ Modal — Voir un véhicule ══ --}}
    <x-admin.form.voir-modal id="modal-view-vehicule" subtitle="Détails du véhicule" iconColor="violet"
        maxWidth="max-w-[500px]">
        @foreach ([
                'nom' => 'Véhicule',
                'categorie' => 'Catégorie',
                'immatriculation' => 'Immatriculation',
                'caracteristiques' => 'Caractéristiques',
                'prix_jour' => 'Prix par jour',
                'visibilite' => 'Visibilité',
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
        subtitleId="delete-modal-subtitle" subtitle="Il sera retiré du site. Impossible s'il a des réservations à venir."
        action="" formId="delete-vehicule-form" maxWidth="max-w-[420px]" />

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

                document.getElementById('edit-vehicule-form').action = action;

                // Remplit chaque champ dont l'id est edit-{nom} (cases à cocher incluses)
                Object.entries(data).forEach(([key, value]) => {
                    const el = document.getElementById(`edit-${key}`);
                    if (!el) return;
                    if (el.type === 'checkbox') el.checked = !!value;
                    else el.value = value ?? '';
                });

                // Réinitialise le champ photo (un input file ne peut pas être pré-rempli)
                const img = document.getElementById('edit-image');
                if (img) img.value = '';

                document.getElementById('edit-modal-subtitle').textContent = data.nom;
                document.getElementById(`${modalId}-form-action`).value = action;
                document.getElementById(`${modalId}-modal-subtitle`).value = data.nom;

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
