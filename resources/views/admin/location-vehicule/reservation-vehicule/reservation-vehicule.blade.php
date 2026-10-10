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
        $fromStatut = old('_modal') === 'modal-statut-reservation';
        $fromEdit = old('_modal') === 'modal-edit-reservation';

        $statutBadge = [
            'en_attente' => 'bg-amber-500/10 text-amber-700 dark:text-amber-300',
            'confirmee' => 'bg-pslb-cyan-500/10 text-pslb-cyan-700 dark:text-pslb-cyan-300',
            'terminee' => 'bg-pslb-blue-500/10 text-pslb-blue-700 dark:text-pslb-blue-200',
            'refusee' => 'bg-red-500/10 text-red-600 dark:text-red-300',
            'annulee' => 'bg-pslb-muted/10 text-pslb-muted dark:text-pslb-blue-300',
        ];

        // ── Helpers de style ──
        $lbl = 'block text-[11.5px] font-medium text-pslb-muted dark:text-pslb-blue-200 mb-1.5';
        $base = 'w-full bg-pslb-surface dark:bg-pslb-blue-900 border rounded-lg py-[8px] px-3 text-[12.5px] text-pslb-ink dark:text-pslb-blue-50 outline-none transition-colors placeholder:text-pslb-muted';
        $cls = fn($name, $bag = 'default') => $base . ' ' . ($errors->getBag($bag)->has($name)
            ? 'border-red-400 dark:border-red-500 focus:border-red-500'
            : 'border-pslb-border dark:border-white/[.08] focus:border-pslb-cyan-500');
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

        $tabBase = 'inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-[12px] font-medium no-underline transition-colors border';
        $tabOn = 'bg-pslb-blue-800 text-white border-pslb-blue-800';
        $tabOff = 'bg-white dark:bg-pslb-blue-800 text-pslb-muted dark:text-pslb-blue-200 border-pslb-border dark:border-white/[.08] hover:text-pslb-blue-800 dark:hover:text-white';
    @endphp

    {{-- ══ En-tête de page ══ --}}
    <x-admin.page-header title="Réservations" description="Demandes et réservations de véhicules"
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
            ['label' => 'ESTIMATION'],
            ['label' => 'STATUT'],
            ['label' => 'ACTIONS'],
        ]" tableId="datatable-reservations" item-label="réservations">
        @foreach ($reservations as $reservation)
            @php
                $debut = $reservation->date_debut;
                $fin = $reservation->date_fin;
                $jours = (int) $debut->diffInDays($fin) + 1;
                $periode = $debut->format('d/m/Y') . ' → ' . $fin->format('d/m/Y');
                $veh = $reservation->vehicule;
                $vehiculeNom = $veh ? $veh->nom . ($veh->trashed() ? ' (supprimé)' : '') : 'À définir';
                $estimation = $veh && $veh->prix_jour ? number_format($jours * $veh->prix_jour, 0, ',', ' ') . ' FCFA' : '—';
                $conduite = $reservation->avec_chauffeur ? 'Avec chauffeur' : 'Sans chauffeur';
                $statutLabel = $statuts[$reservation->statut] ?? $reservation->statut;
                // Données envoyées au modal « Changer le statut »
                $statutData = [
                    'id' => $reservation->id,
                    'reference' => $reservation->reference,
                    'nom' => $reservation->nom,
                    'statut' => $reservation->statut,
                    'vehicule_id' => $reservation->vehicule_id,
                    'note_interne' => $reservation->note_interne,
                    'details' => $reservation->telephone . ' · ' . $conduite . ' · ' . $periode . ' (' . $jours . ' j)',
                    'message' => $reservation->message,
                ];

                // Données envoyées au modal « Modifier »
                $editData = [
                    'id' => $reservation->id,
                    'nom' => $reservation->nom,
                    'telephone' => $reservation->telephone,
                    'email' => $reservation->email,
                    'vehicule_id' => $reservation->vehicule_id,
                    'avec_chauffeur' => (bool) $reservation->avec_chauffeur,
                    'date_debut' => $debut->format('Y-m-d'),
                    'date_fin' => $fin->format('Y-m-d'),
                    'lieu_prise' => $reservation->lieu_prise,
                    'message' => $reservation->message,
                ];
            @endphp
            <tr class="tr-hov border-b border-pslb-border dark:border-white/[.08] table-row"
                data-name="{{ $reservation->reference }} {{ $reservation->nom }} {{ $vehiculeNom }}">
                <td class="px-4 py-[11px]">
                    <div class="flex items-center gap-[10px]">
                        <div
                            class="w-[32px] h-[32px] rounded-full bg-pslb-cyan-500/15
                                                                            flex items-center justify-center
                                                                            text-pslb-cyan-700 dark:text-pslb-cyan-300 font-display text-[10px] font-bold shrink-0">
                            {{ getInitialesStartEnd($reservation->nom) }}
                        </div>
                        <div>
                            <p class="font-medium text-[12.5px] text-pslb-ink dark:text-pslb-blue-50 m-0">
                                {{ $reservation->nom }}
                            </p>
                            <p class="text-[10px] text-pslb-muted dark:text-pslb-blue-300 m-0">
                                {{ $reservation->reference }} · {{ $reservation->telephone }}
                            </p>
                        </div>
                    </div>
                </td>
                <td class="px-3 py-[11px] text-[12.5px] text-pslb-muted dark:text-pslb-blue-200 hidden sm:table-cell">
                    {{ $vehiculeNom }}
                    <span class="block text-[10px] text-pslb-muted dark:text-pslb-blue-300">{{ $conduite }}</span>
                </td>
                <td class="px-3 py-[11px] text-[12px] text-pslb-ink dark:text-pslb-blue-50 whitespace-nowrap">
                    {{ $periode }}
                    <span class="block text-[10px] text-pslb-muted dark:text-pslb-blue-300">
                        {{ $jours }} {{ $jours > 1 ? 'jours' : 'jour' }}
                    </span>
                </td>
                <td class="px-3 py-[11px] text-[12.5px] font-medium text-pslb-ink dark:text-pslb-blue-50 whitespace-nowrap">
                    {{ $estimation }}
                </td>
                <td class="px-3 py-[11px]">
                    {{-- Le badge ouvre aussi le modal de changement de statut --}}
                    <button type="button" onclick="openStatutModal({{ Js::from($statutData) }})" title="Changer le statut"
                        class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full border-0 text-[11px] font-medium cursor-pointer transition-opacity hover:opacity-75 {{ $statutBadge[$reservation->statut] ?? '' }}">
                        {{ $statutLabel }}
                        <svg class="w-[10px] h-[10px]" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                </td>
                <td class="px-4 py-[11px] text-right">
                    <div class="flex items-center justify-end gap-1.5">
                        <x-admin.ux.button.modifier-modal onclick="openEditModal({{ Js::from($editData) }})" />
                        {{-- Changer le statut --}}
                        <button type="button" onclick="openStatutModal({{ Js::from($statutData) }})" title="Changer le statut"
                            class="w-[28px] h-[28px] rounded-lg border border-pslb-border dark:border-white/[.10]
                                                                           bg-pslb-surface dark:bg-pslb-blue-900 text-pslb-muted
                                                                           hover:text-pslb-cyan-600 dark:hover:text-pslb-cyan-300 hover:border-pslb-cyan-500/50
                                                                           flex items-center justify-center cursor-pointer transition-colors">
                            <svg class="w-[13px] h-[13px]" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24">
                                <path
                                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                        </button>
                        <x-admin.ux.button.voir-modal modal="modal-view-reservation" subtitle="{{ $reservation->nom }}"
                            tip="Voir la réservation" :fields="[
                    'reference' => $reservation->reference ?: '—',
                    'nom' => $reservation->nom,
                    'telephone' => $reservation->telephone,
                    'email' => $reservation->email ?: '—',
                    'vehicule' => $vehiculeNom,
                    'periode' => $periode . ' (' . $jours . ' j)',
                    'conduite' => $conduite,
                    'lieu_prise' => $reservation->lieu_prise ?: '—',
                    'estimation' => $estimation,
                    'statut' => $statutLabel,
                    'message' => $reservation->message ?: '—',
                    'note_interne' => $reservation->note_interne ?: '—',
                    'recu_le' => $reservation->created_at?->format('d/m/Y à H:i') ?? '—',
                ]" />
                        <x-admin.ux.button.supprimer-modal
                            onclick="openDeleteModal({{ $reservation->id }}, {{ Js::from($reservation->nom) }})" />
                    </div>
                </td>
            </tr>
        @endforeach
    </x-admin.table.datatable>

    {{-- ══ MODAL — Nouvelle réservation ══ --}}
    <x-admin.form.ajout-modal id="modal-add-reservation" title="Nouvelle réservation"
        subtitle="Saisie manuelle d'une réservation (téléphone, comptoir…)" :action="route('admin.reservations.store')"
        submitLabel="Créer la réservation" maxWidth="max-w-[680px]">

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">

            {{-- Nom --}}
            <div>
                <label class="{{ $lbl }}">Nom du client <span class="text-red-500">*</span></label>
                <input type="text" name="nom" value="{{ $fromAdd ? old('nom') : '' }}" placeholder="Ex : Koffi Adjovi"
                    class="{{ $cls('nom') }}" required />
                {!! $err('nom') !!}
            </div>

            {{-- Téléphone --}}
            <div>
                <label class="{{ $lbl }}">Téléphone <span class="text-red-500">*</span></label>
                <input type="tel" name="telephone" value="{{ $fromAdd ? old('telephone') : '' }}"
                    placeholder="+229 XX XX XX XX" class="{{ $cls('telephone') }}" required />
                {!! $err('telephone') !!}
            </div>

            {{-- Email --}}
            <div class="sm:col-span-2">
                <label class="{{ $lbl }}">E-mail</label>
                <input type="email" name="email" value="{{ $fromAdd ? old('email') : '' }}" placeholder="client@exemple.com"
                    class="{{ $cls('email') }}" />
                {!! $err('email') !!}
            </div>

            {{-- Véhicule --}}
            <div class="sm:col-span-2">
                <label class="{{ $lbl }}">Véhicule</label>
                <select name="vehicule_id" class="{{ $cls('vehicule_id') }} cursor-pointer">
                    <option value="">— À définir —</option>
                    @foreach ($vehicules as $vehicule)
                        <option value="{{ $vehicule->id }}" @selected($fromAdd && old('vehicule_id') == $vehicule->id)>
                            {{ $vehicule->nom }}{{ $vehicule->immatriculation ? ' — ' . $vehicule->immatriculation : '' }}{{ !$vehicule->est_disponible ? ' (masqué)' : '' }}
                        </option>
                    @endforeach
                </select>
                {!! $err('vehicule_id') !!}
            </div>

            {{-- Dates --}}
            <div>
                <label class="{{ $lbl }}">Date de début <span class="text-red-500">*</span></label>
                <input type="date" name="date_debut" value="{{ $fromAdd ? old('date_debut') : '' }}"
                    min="{{ now()->toDateString() }}" class="{{ $cls('date_debut') }}" required />
                {!! $err('date_debut') !!}
            </div>
            <div>
                <label class="{{ $lbl }}">Date de fin <span class="text-red-500">*</span></label>
                <input type="date" name="date_fin" value="{{ $fromAdd ? old('date_fin') : '' }}"
                    min="{{ now()->toDateString() }}" class="{{ $cls('date_fin') }}" required />
                {!! $err('date_fin') !!}
            </div>

            {{-- Lieu de prise en charge --}}
            <div class="sm:col-span-2">
                <label class="{{ $lbl }}">Lieu de prise en charge</label>
                <input type="text" name="lieu_prise" value="{{ $fromAdd ? old('lieu_prise') : '' }}"
                    placeholder="Ex : Aéroport, domicile, hôtel…" class="{{ $cls('lieu_prise') }}" />
                {!! $err('lieu_prise') !!}
            </div>

            {{-- Statut --}}
            <div>
                <label class="{{ $lbl }}">Statut <span class="text-red-500">*</span></label>
                <select name="statut" class="{{ $cls('statut') }} cursor-pointer" required>
                    @foreach ($statuts as $value => $label)
                        <option value="{{ $value }}" @selected($fromAdd ? old('statut') === $value : $value === 'en_attente')>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
                {!! $err('statut') !!}
            </div>

            {{-- Avec chauffeur --}}
            <div class="self-end">
                {!! $toggle('avec_chauffeur', 'add-avec_chauffeur', 'Avec chauffeur', 'Conduite assurée par PSLB', $fromAdd && old('avec_chauffeur')) !!}
            </div>

            {{-- Message du client --}}
            <div class="sm:col-span-2">
                <label class="{{ $lbl }}">Demande du client</label>
                <textarea name="message" rows="2" placeholder="Événement, nombre de passagers, trajet prévu…"
                    class="{{ $cls('message') }} resize-none">{{ $fromAdd ? old('message') : '' }}</textarea>
                {!! $err('message') !!}
            </div>

            {{-- Note interne --}}
            <div class="sm:col-span-2">
                <label class="{{ $lbl }}">Note interne <span class="font-normal text-pslb-muted ml-1">(non visible par le
                        client)</span></label>
                <textarea name="note_interne" rows="2" placeholder="Caution, suivi, rappel…"
                    class="{{ $cls('note_interne') }} resize-none">{{ $fromAdd ? old('note_interne') : '' }}</textarea>
                {!! $err('note_interne') !!}
            </div>

        </div>
    </x-admin.form.ajout-modal>

    {{-- ══ MODAL — Modifier une réservation ══ --}}
    <x-admin.form.modifier-modal id="modal-edit-reservation" title="Modifier la réservation"
        subtitleId="edit-modal-subtitle" subtitle="—" action="" formId="edit-reservation-form" method="PUT" errorBag="edit"
        submitLabel="Enregistrer" submitColor="bg-pslb-blue-800 hover:bg-pslb-blue-700" maxWidth="max-w-[680px]">

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">

            {{-- Nom --}}
            <div>
                <label class="{{ $lbl }}">Nom du client <span class="text-red-500">*</span></label>
                <input type="text" name="nom" id="edit-nom" value="{{ $fromEdit ? old('nom') : '' }}"
                    class="{{ $cls('nom', 'edit') }}" required />
                {!! $err('nom', 'edit') !!}
            </div>

            {{-- Téléphone --}}
            <div>
                <label class="{{ $lbl }}">Téléphone <span class="text-red-500">*</span></label>
                <input type="tel" name="telephone" id="edit-telephone" value="{{ $fromEdit ? old('telephone') : '' }}"
                    class="{{ $cls('telephone', 'edit') }}" required />
                {!! $err('telephone', 'edit') !!}
            </div>

            {{-- Email --}}
            <div class="sm:col-span-2">
                <label class="{{ $lbl }}">E-mail</label>
                <input type="email" name="email" id="edit-email" value="{{ $fromEdit ? old('email') : '' }}"
                    class="{{ $cls('email', 'edit') }}" />
                {!! $err('email', 'edit') !!}
            </div>

            {{-- Véhicule --}}
            <div class="sm:col-span-2">
                <label class="{{ $lbl }}">Véhicule</label>
                <select name="vehicule_id" id="edit-vehicule_id" class="{{ $cls('vehicule_id', 'edit') }} cursor-pointer">
                    <option value="">— À définir —</option>
                    @foreach ($vehicules as $vehicule)
                        <option value="{{ $vehicule->id }}" @selected($fromEdit && old('vehicule_id') == $vehicule->id)>
                            {{ $vehicule->nom }}{{ $vehicule->immatriculation ? ' — ' . $vehicule->immatriculation : '' }}{{ !$vehicule->est_disponible ? ' (masqué)' : '' }}
                        </option>
                    @endforeach
                </select>
                {!! $err('vehicule_id', 'edit') !!}
            </div>

            {{-- Dates --}}
            <div>
                <label class="{{ $lbl }}">Date de début <span class="text-red-500">*</span></label>
                <input type="date" name="date_debut" id="edit-date_debut" value="{{ $fromEdit ? old('date_debut') : '' }}"
                    class="{{ $cls('date_debut', 'edit') }}" required />
                {!! $err('date_debut', 'edit') !!}
            </div>
            <div>
                <label class="{{ $lbl }}">Date de fin <span class="text-red-500">*</span></label>
                <input type="date" name="date_fin" id="edit-date_fin" value="{{ $fromEdit ? old('date_fin') : '' }}"
                    class="{{ $cls('date_fin', 'edit') }}" required />
                {!! $err('date_fin', 'edit') !!}
            </div>

            {{-- Lieu de prise en charge --}}
            <div>
                <label class="{{ $lbl }}">Lieu de prise en charge</label>
                <input type="text" name="lieu_prise" id="edit-lieu_prise" value="{{ $fromEdit ? old('lieu_prise') : '' }}"
                    class="{{ $cls('lieu_prise', 'edit') }}" />
                {!! $err('lieu_prise', 'edit') !!}
            </div>

            {{-- Avec chauffeur --}}
            <div class="self-end">
                {!! $toggle('avec_chauffeur', 'edit-avec_chauffeur', 'Avec chauffeur', 'Conduite assurée par PSLB', $fromEdit && old('avec_chauffeur')) !!}
            </div>

            {{-- Message du client --}}
            <div class="sm:col-span-2">
                <label class="{{ $lbl }}">Demande du client</label>
                <textarea name="message" id="edit-message" rows="2"
                    class="{{ $cls('message', 'edit') }} resize-none">{{ $fromEdit ? old('message') : '' }}</textarea>
                {!! $err('message', 'edit') !!}
            </div>

        </div>
    </x-admin.form.modifier-modal>

    {{-- ══ MODAL — Changer le statut ══ --}}
    <x-admin.form.modifier-modal id="modal-statut-reservation" title="Changer le statut" subtitleId="statut-modal-subtitle"
        subtitle="—" action="" formId="statut-reservation-form" method="PATCH" errorBag="edit" submitLabel="Mettre à jour"
        submitColor="bg-pslb-blue-800 hover:bg-pslb-blue-700" maxWidth="max-w-[520px]">

        {{-- Résumé de la demande (rempli en JS) --}}
        <div class="p-3 rounded-lg bg-pslb-surface dark:bg-pslb-blue-900 border border-pslb-border dark:border-white/[.08]">
            <p id="statut-resume-client" class="text-[12.5px] font-medium text-pslb-ink dark:text-pslb-blue-50 m-0">—</p>
            <p id="statut-resume-details" class="text-[11px] text-pslb-muted dark:text-pslb-blue-300 mt-0.5 m-0">—</p>
            <p id="statut-resume-message"
                class="hidden text-[11.5px] italic text-pslb-muted dark:text-pslb-blue-200 mt-2 m-0 leading-relaxed"></p>
        </div>

        {{-- Statut --}}
        <div>
            <label class="{{ $lbl }}">Nouveau statut <span class="text-red-500">*</span></label>
            <select name="statut" id="st-statut" class="{{ $cls('statut', 'edit') }} cursor-pointer" required>
                @foreach ($statuts as $value => $label)
                    <option value="{{ $value }}" @selected($fromStatut && old('statut') === $value)>{{ $label }}
                    </option>
                @endforeach
            </select>
            {!! $err('statut', 'edit') !!}
        </div>

        {{-- Véhicule attribué --}}
        <div>
            <label class="{{ $lbl }}">Véhicule attribué <span class="font-normal text-pslb-muted ml-1">(obligatoire pour
                    confirmer)</span></label>
            <select name="vehicule_id" id="st-vehicule_id" class="{{ $cls('vehicule_id', 'edit') }} cursor-pointer">
                <option value="">— À définir —</option>
                @foreach ($vehicules as $vehicule)
                    <option value="{{ $vehicule->id }}" @selected($fromStatut && old('vehicule_id') == $vehicule->id)>
                        {{ $vehicule->nom }}{{ $vehicule->immatriculation ? ' — ' . $vehicule->immatriculation : '' }}{{ !$vehicule->est_disponible ? ' (masqué)' : '' }}
                    </option>
                @endforeach
            </select>
            {!! $err('vehicule_id', 'edit') !!}
        </div>

        {{-- Note interne --}}
        <div>
            <label class="{{ $lbl }}">Note interne <span class="font-normal text-pslb-muted ml-1">(motif, suivi — non
                    visible par le client)</span></label>
            <textarea name="note_interne" id="st-note_interne" rows="3"
                placeholder="Ex : client rappelé, caution à prévoir, motif du refus…"
                class="{{ $cls('note_interne', 'edit') }} resize-none">{{ $fromStatut ? old('note_interne') : '' }}</textarea>
            {!! $err('note_interne', 'edit') !!}
        </div>
    </x-admin.form.modifier-modal>

    {{-- ══ Modal — Voir une réservation ══ --}}
    <x-admin.form.voir-modal id="modal-view-reservation" subtitle="Détails de la réservation" iconColor="violet"
        maxWidth="max-w-[520px]">
        @foreach ([
                'reference' => 'Référence',
                'nom' => 'Client',
                'telephone' => 'Téléphone',
                'email' => 'E-mail',
                'vehicule' => 'Véhicule',
                'periode' => 'Période',
                'conduite' => 'Conduite',
                'lieu_prise' => 'Lieu de prise en charge',
                'estimation' => 'Estimation du prix',
                'statut' => 'Statut',
                'message' => 'Demande du client',
                'note_interne' => 'Note interne',
                'recu_le' => 'Reçue le',
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
        subtitleId="delete-modal-subtitle" subtitle="Une réservation confirmée à venir doit d'abord être annulée." action=""
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

            window.openStatutModal = (data) => {
                const modalId = 'modal-statut-reservation';
                const action = `/admin/reservations/${data.id}/statut`;

                document.getElementById('statut-reservation-form').action = action;

                // Champs du formulaire : statut, véhicule, note interne
                document.getElementById('st-statut').value = data.statut ?? '';
                document.getElementById('st-vehicule_id').value = data.vehicule_id ?? '';
                document.getElementById('st-note_interne').value = data.note_interne ?? '';

                // Résumé de la demande
                document.getElementById('statut-resume-client').textContent =
                    `${data.reference ? data.reference + ' — ' : ''}${data.nom}`;
                document.getElementById('statut-resume-details').textContent = data.details ?? '';
                const msg = document.getElementById('statut-resume-message');
                msg.textContent = data.message ? `« ${data.message} »` : '';
                msg.classList.toggle('hidden', !data.message);

                document.getElementById('statut-modal-subtitle').textContent = data.nom;
                document.getElementById(`${modalId}-form-action`).value = action;
                document.getElementById(`${modalId}-modal-subtitle`).value = data.nom;

                openModal(modalId);
            };

            window.openEditModal = (data) => {
                const modalId = 'modal-edit-reservation';
                const action = `/admin/reservations/${data.id}`;

                document.getElementById('edit-reservation-form').action = action;

                // Remplit chaque champ dont l'id est edit-{nom} (case à cocher incluse)
                Object.entries(data).forEach(([key, value]) => {
                    const el = document.getElementById(`edit-${key}`);
                    if (!el) return;
                    if (el.type === 'checkbox') el.checked = !!value;
                    else el.value = value ?? '';
                });

                document.getElementById('edit-modal-subtitle').textContent = data.nom;
                document.getElementById(`${modalId}-form-action`).value = action;
                document.getElementById(`${modalId}-modal-subtitle`).value = data.nom;

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
