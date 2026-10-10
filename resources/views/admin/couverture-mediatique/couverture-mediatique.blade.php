@extends('layouts.admin.app', ['title' => 'Couverture médiatique'])

@section('content')
    <style>
        #filter-per-page-select+.ts-wrapper {
            min-width: 160px;
            max-width: 220px;
            width: auto;
        }
    </style>
    @php
        $fromAdd = old('_modal') === 'modal-add-demande';
        $fromEdit = old('_modal') === 'modal-edit-demande';
        $fromStatut = old('_modal') === 'modal-statut-demande';

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
        // Case à cocher « service » (photos, vidéos…)
        $serviceBox = fn($key, $label, $id, $checked) => '<label class="flex items-center gap-2 px-3 py-2.5 rounded-lg border border-pslb-border dark:border-white/[.08] bg-pslb-surface dark:bg-pslb-blue-900 cursor-pointer text-[12px] text-pslb-ink dark:text-pslb-blue-100 transition-colors hover:border-pslb-cyan-500/60 has-[:checked]:border-pslb-cyan-500 has-[:checked]:bg-pslb-cyan-500/10">'
            . '<input type="checkbox" name="services[]" value="' . $key . '" id="' . $id . '" class="accent-pslb-cyan-500"' . ($checked ? ' checked' : '') . '>'
            . e($label) . '</label>';

        $tabBase = 'inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-[12px] font-medium no-underline transition-colors border';
        $tabOn = 'bg-pslb-blue-800 text-white border-pslb-blue-800';
        $tabOff = 'bg-white dark:bg-pslb-blue-800 text-pslb-muted dark:text-pslb-blue-200 border-pslb-border dark:border-white/[.08] hover:text-pslb-blue-800 dark:hover:text-white';
    @endphp

    {{-- ══ En-tête de page ══ --}}
    <x-admin.page-header title="Couverture médiatique" description="Demandes de photos, vidéos, interviews et podcasts"
        :count="$demandes->count()" count-label="demandes">
        <button onclick="openModal('modal-add-demande')" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-pslb-blue-800 hover:bg-pslb-blue-700
                       text-white text-[13px] font-medium cursor-pointer transition-colors border-0">
            <svg class="w-[14px] h-[14px]" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path d="M12 4v16m8-8H4" />
            </svg>
            Nouvelle demande
        </button>
    </x-admin.page-header>

    {{-- ══ Onglets de statut ══ --}}
    <div class="flex flex-wrap gap-1.5 mb-3">
        <a href="{{ route('admin.demandes-couverture.index') }}" class="{{ $tabBase }} {{ !$filtre ? $tabOn : $tabOff }}">
            Toutes <span class="opacity-70">{{ $counts->sum() }}</span>
        </a>
        @foreach ($statuts as $value => $label)
            <a href="{{ route('admin.demandes-couverture.index', ['statut' => $value]) }}"
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
            {{ $demandes->count() }}
        </span>
    </div>

    {{-- ══ Tableau des demandes ══ --}}
    <x-admin.table.datatable :columns="[
            ['label' => 'DEMANDEUR', 'sort' => 'name'],
            ['label' => 'ÉVÉNEMENT'],
            ['label' => 'DATE'],
            ['label' => 'SERVICES'],
            ['label' => 'DEVIS'],
            ['label' => 'STATUT'],
            ['label' => 'ACTIONS'],
        ]" tableId="datatable-demandes" item-label="demandes">
        @foreach ($demandes as $demande)
            @php
                $debut = $demande->date_debut;
                $fin = $demande->date_fin;
                $dateTxt = $fin && !$fin->isSameDay($debut)
                    ? $debut->format('d/m/Y') . ' → ' . $fin->format('d/m/Y')
                    : $debut->format('d/m/Y');
                $servicesTxt = $demande->servicesLabel();
                $devis = $demande->montant ? number_format($demande->montant, 0, ',', ' ') . ' FCFA' : '—';
                $statutLabel = $statuts[$demande->statut] ?? $demande->statut;

                // Données envoyées au modal « Changer le statut »
                $statutData = [
                    'id' => $demande->id,
                    'reference' => $demande->reference,
                    'nom' => $demande->nom,
                    'statut' => $demande->statut,
                    'montant' => $demande->montant,
                    'note_interne' => $demande->note_interne,
                    'details' => collect([$demande->telephone, $demande->organisation, $dateTxt])->filter()->implode(' · '),
                    'evenement' => $demande->titre_evenement . ($demande->lieu ? ' — ' . $demande->lieu : ''),
                    'services' => $servicesTxt,
                    'message' => $demande->message,
                ];
                // Données envoyées au modal « Modifier »
                $editData = [
                    'id' => $demande->id,
                    'nom' => $demande->nom,
                    'telephone' => $demande->telephone,
                    'email' => $demande->email,
                    'organisation' => $demande->organisation,
                    'titre_evenement' => $demande->titre_evenement,
                    'lieu' => $demande->lieu,
                    'date_debut' => $debut->format('Y-m-d'),
                    'date_fin' => $fin?->format('Y-m-d'),
                    'services' => $demande->services ?? [],
                    'message' => $demande->message,
                ];
            @endphp
            <tr class="tr-hov border-b border-pslb-border dark:border-white/[.08] table-row"
                data-name="{{ $demande->reference }} {{ $demande->nom }} {{ $demande->organisation }} {{ $demande->titre_evenement }}">
                <td class="px-4 py-[11px]">
                    <div class="flex items-center gap-[10px]">
                        <div
                            class="w-[32px] h-[32px] rounded-full bg-pslb-cyan-500/15
                                            flex items-center justify-center
                                            text-pslb-cyan-700 dark:text-pslb-cyan-300 font-display text-[10px] font-bold shrink-0">
                            {{ getInitialesStartEnd($demande->nom) }}
                        </div>
                        <div>
                            <p class="font-medium text-[12.5px] text-pslb-ink dark:text-pslb-blue-50 m-0">
                                {{ $demande->nom }}
                                @if ($demande->organisation)
                                    <span class="text-pslb-muted dark:text-pslb-blue-300 font-normal">·
                                        {{ $demande->organisation }}</span>
                                @endif
                            </p>
                            <p class="text-[10px] text-pslb-muted dark:text-pslb-blue-300 m-0">
                                {{ $demande->reference }} · {{ $demande->telephone }}
                            </p>
                        </div>
                    </div>
                </td>
                <td class="px-3 py-[11px] text-[12.5px] text-pslb-ink dark:text-pslb-blue-50 hidden sm:table-cell">
                    {{ $demande->titre_evenement }}
                    @if ($demande->lieu)
                        <span class="block text-[10px] text-pslb-muted dark:text-pslb-blue-300">{{ $demande->lieu }}</span>
                    @endif
                </td>
                <td class="px-3 py-[11px] text-[12px] text-pslb-ink dark:text-pslb-blue-50 whitespace-nowrap">
                    {{ $dateTxt }}
                </td>
                <td class="px-3 py-[11px] hidden md:table-cell">
                    <div class="flex flex-wrap gap-1">
                        @foreach ($demande->services ?? [] as $service)
                            <span
                                class="px-2 py-0.5 rounded-full text-[10px] font-medium bg-pslb-blue-50 text-pslb-blue-700 dark:bg-pslb-blue-700 dark:text-pslb-blue-50">
                                {{ $servicesList[$service] ?? $service }}
                            </span>
                        @endforeach
                    </div>
                </td>
                <td class="px-3 py-[11px] text-[12.5px] font-medium text-pslb-ink dark:text-pslb-blue-50 whitespace-nowrap">
                    {{ $devis }}
                </td>
                <td class="px-3 py-[11px]">
                    {{-- Le badge ouvre aussi le modal de changement de statut --}}
                    <button type="button" onclick="openStatutModal({{ Js::from($statutData) }})" title="Changer le statut"
                        class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full border-0 text-[11px] font-medium cursor-pointer transition-opacity hover:opacity-75 {{ $statutBadge[$demande->statut] ?? '' }}">
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
                        <x-admin.ux.button.voir-modal modal="modal-view-demande" subtitle="{{ $demande->nom }}"
                            tip="Voir la demande" :fields="[
                    'reference' => $demande->reference ?: '—',
                    'nom' => $demande->nom,
                    'telephone' => $demande->telephone,
                    'email' => $demande->email ?: '—',
                    'organisation' => $demande->organisation ?: '—',
                    'evenement' => $demande->titre_evenement,
                    'lieu' => $demande->lieu ?: '—',
                    'date' => $dateTxt,
                    'services' => $servicesTxt ?: '—',
                    'message' => $demande->message ?: '—',
                    'devis' => $devis,
                    'statut' => $statutLabel,
                    'note_interne' => $demande->note_interne ?: '—',
                    'recu_le' => $demande->created_at?->format('d/m/Y à H:i') ?? '—',
                ]" />
                        <x-admin.ux.button.supprimer-modal
                            onclick="openDeleteModal({{ $demande->id }}, {{ Js::from($demande->nom) }})" />
                    </div>
                </td>
            </tr>
        @endforeach
    </x-admin.table.datatable>

    {{-- ══ MODAL — Nouvelle demande ══ --}}
    <x-admin.form.ajout-modal id="modal-add-demande" title="Nouvelle demande"
        subtitle="Saisie manuelle d'une demande (téléphone, rencontre…)" :action="route('admin.demandes-couverture.store')"
        submitLabel="Créer la demande" maxWidth="max-w-[700px]">

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">

            <div>
                <label class="{{ $lbl }}">Nom du demandeur <span class="text-red-500">*</span></label>
                <input type="text" name="nom" value="{{ $fromAdd ? old('nom') : '' }}" placeholder="Ex : Koffi Adjovi"
                    class="{{ $cls('nom') }}" required />
                {!! $err('nom') !!}
            </div>

            <div>
                <label class="{{ $lbl }}">Téléphone <span class="text-red-500">*</span></label>
                <input type="tel" name="telephone" value="{{ $fromAdd ? old('telephone') : '' }}"
                    placeholder="+229 XX XX XX XX" class="{{ $cls('telephone') }}" required />
                {!! $err('telephone') !!}
            </div>

            <div>
                <label class="{{ $lbl }}">E-mail</label>
                <input type="email" name="email" value="{{ $fromAdd ? old('email') : '' }}"
                    placeholder="contact@exemple.com" class="{{ $cls('email') }}" />
                {!! $err('email') !!}
            </div>

            <div>
                <label class="{{ $lbl }}">Organisation</label>
                <input type="text" name="organisation" value="{{ $fromAdd ? old('organisation') : '' }}"
                    placeholder="Entreprise, association, église…" class="{{ $cls('organisation') }}" />
                {!! $err('organisation') !!}
            </div>

            <div class="sm:col-span-2">
                <label class="{{ $lbl }}">Nom de l'événement ou du projet <span class="text-red-500">*</span></label>
                <input type="text" name="titre_evenement" value="{{ $fromAdd ? old('titre_evenement') : '' }}"
                    placeholder="Ex : Conférence annuelle, lancement de produit…" class="{{ $cls('titre_evenement') }}"
                    required />
                {!! $err('titre_evenement') !!}
            </div>

            <div class="sm:col-span-2">
                <label class="{{ $lbl }}">Lieu</label>
                <input type="text" name="lieu" value="{{ $fromAdd ? old('lieu') : '' }}"
                    placeholder="Ex : Cotonou — Palais des congrès" class="{{ $cls('lieu') }}" />
                {!! $err('lieu') !!}
            </div>

            <div>
                <label class="{{ $lbl }}">Date de début <span class="text-red-500">*</span></label>
                <input type="date" name="date_debut" value="{{ $fromAdd ? old('date_debut') : '' }}"
                    min="{{ now()->toDateString() }}" class="{{ $cls('date_debut') }}" required />
                {!! $err('date_debut') !!}
            </div>

            <div>
                <label class="{{ $lbl }}">Date de fin <span class="font-normal text-pslb-muted ml-1">(si plusieurs
                        jours)</span></label>
                <input type="date" name="date_fin" value="{{ $fromAdd ? old('date_fin') : '' }}"
                    min="{{ now()->toDateString() }}" class="{{ $cls('date_fin') }}" />
                {!! $err('date_fin') !!}
            </div>

            {{-- Services --}}
            <div class="sm:col-span-2">
                <label class="{{ $lbl }}">Services souhaités <span class="text-red-500">*</span></label>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-2">
                    @foreach ($servicesList as $key => $label)
                        {!! $serviceBox($key, $label, 'add-services-' . $key, $fromAdd && in_array($key, old('services', []))) !!}
                    @endforeach
                </div>
                {!! $err('services') !!}
            </div>

            <div class="sm:col-span-2">
                <label class="{{ $lbl }}">Description du besoin</label>
                <textarea name="message" rows="3" placeholder="Programme, nombre d'invités, livrables attendus…"
                    class="{{ $cls('message') }} resize-none">{{ $fromAdd ? old('message') : '' }}</textarea>
                {!! $err('message') !!}
            </div>

        </div>
    </x-admin.form.ajout-modal>

    {{-- ══ MODAL — Modifier une demande ══ --}}
    <x-admin.form.modifier-modal id="modal-edit-demande" title="Modifier la demande" subtitleId="edit-modal-subtitle"
        subtitle="—" action="" formId="edit-demande-form" method="PUT" errorBag="edit" submitLabel="Enregistrer"
        submitColor="bg-pslb-blue-800 hover:bg-pslb-blue-700" maxWidth="max-w-[700px]">

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">

            <div>
                <label class="{{ $lbl }}">Nom du demandeur <span class="text-red-500">*</span></label>
                <input type="text" name="nom" id="edit-nom" value="{{ $fromEdit ? old('nom') : '' }}"
                    class="{{ $cls('nom', 'edit') }}" required />
                {!! $err('nom', 'edit') !!}
            </div>

            <div>
                <label class="{{ $lbl }}">Téléphone <span class="text-red-500">*</span></label>
                <input type="tel" name="telephone" id="edit-telephone" value="{{ $fromEdit ? old('telephone') : '' }}"
                    class="{{ $cls('telephone', 'edit') }}" required />
                {!! $err('telephone', 'edit') !!}
            </div>

            <div>
                <label class="{{ $lbl }}">E-mail</label>
                <input type="email" name="email" id="edit-email" value="{{ $fromEdit ? old('email') : '' }}"
                    class="{{ $cls('email', 'edit') }}" />
                {!! $err('email', 'edit') !!}
            </div>

            <div>
                <label class="{{ $lbl }}">Organisation</label>
                <input type="text" name="organisation" id="edit-organisation"
                    value="{{ $fromEdit ? old('organisation') : '' }}" class="{{ $cls('organisation', 'edit') }}" />
                {!! $err('organisation', 'edit') !!}
            </div>

            <div class="sm:col-span-2">
                <label class="{{ $lbl }}">Nom de l'événement ou du projet <span class="text-red-500">*</span></label>
                <input type="text" name="titre_evenement" id="edit-titre_evenement"
                    value="{{ $fromEdit ? old('titre_evenement') : '' }}" class="{{ $cls('titre_evenement', 'edit') }}"
                    required />
                {!! $err('titre_evenement', 'edit') !!}
            </div>

            <div class="sm:col-span-2">
                <label class="{{ $lbl }}">Lieu</label>
                <input type="text" name="lieu" id="edit-lieu" value="{{ $fromEdit ? old('lieu') : '' }}"
                    class="{{ $cls('lieu', 'edit') }}" />
                {!! $err('lieu', 'edit') !!}
            </div>

            <div>
                <label class="{{ $lbl }}">Date de début <span class="text-red-500">*</span></label>
                <input type="date" name="date_debut" id="edit-date_debut" value="{{ $fromEdit ? old('date_debut') : '' }}"
                    class="{{ $cls('date_debut', 'edit') }}" required />
                {!! $err('date_debut', 'edit') !!}
            </div>

            <div>
                <label class="{{ $lbl }}">Date de fin <span class="font-normal text-pslb-muted ml-1">(si plusieurs
                        jours)</span></label>
                <input type="date" name="date_fin" id="edit-date_fin" value="{{ $fromEdit ? old('date_fin') : '' }}"
                    class="{{ $cls('date_fin', 'edit') }}" />
                {!! $err('date_fin', 'edit') !!}
            </div>

            {{-- Services --}}
            <div class="sm:col-span-2">
                <label class="{{ $lbl }}">Services souhaités <span class="text-red-500">*</span></label>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-2">
                    @foreach ($servicesList as $key => $label)
                        {!! $serviceBox($key, $label, 'edit-services-' . $key, $fromEdit && in_array($key, old('services', []))) !!}
                    @endforeach
                </div>
                {!! $err('services', 'edit') !!}
            </div>

            <div class="sm:col-span-2">
                <label class="{{ $lbl }}">Description du besoin</label>
                <textarea name="message" id="edit-message" rows="3"
                    class="{{ $cls('message', 'edit') }} resize-none">{{ $fromEdit ? old('message') : '' }}</textarea>
                {!! $err('message', 'edit') !!}
            </div>

        </div>
    </x-admin.form.modifier-modal>

    {{-- ══ MODAL — Changer le statut ══ --}}
    <x-admin.form.modifier-modal id="modal-statut-demande" title="Changer le statut" subtitleId="statut-modal-subtitle"
        subtitle="—" action="" formId="statut-demande-form" method="PATCH" errorBag="edit" submitLabel="Mettre à jour"
        submitColor="bg-pslb-blue-800 hover:bg-pslb-blue-700" maxWidth="max-w-[520px]">

        {{-- Résumé de la demande (rempli en JS) --}}
        <div class="p-3 rounded-lg bg-pslb-surface dark:bg-pslb-blue-900 border border-pslb-border dark:border-white/[.08]">
            <p id="statut-resume-client" class="text-[12.5px] font-medium text-pslb-ink dark:text-pslb-blue-50 m-0">—</p>
            <p id="statut-resume-details" class="text-[11px] text-pslb-muted dark:text-pslb-blue-300 mt-0.5 m-0">—</p>
            <p id="statut-resume-evenement" class="text-[12px] text-pslb-ink dark:text-pslb-blue-100 mt-2 m-0">—</p>
            <p id="statut-resume-services" class="text-[11px] text-pslb-cyan-700 dark:text-pslb-cyan-300 mt-0.5 m-0">—</p>
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

        {{-- Devis --}}
        <div>
            <label class="{{ $lbl }}">Devis proposé <span class="font-normal text-pslb-muted ml-1">(FCFA — à renseigner
                    avant de confirmer)</span></label>
            <input type="number" name="montant" id="st-montant" min="0" step="1000"
                value="{{ $fromStatut ? old('montant') : '' }}" placeholder="Ex : 150000"
                class="{{ $cls('montant', 'edit') }}" />
            {!! $err('montant', 'edit') !!}
        </div>

        {{-- Note interne --}}
        <div>
            <label class="{{ $lbl }}">Note interne <span class="font-normal text-pslb-muted ml-1">(motif, suivi — non
                    visible par le client)</span></label>
            <textarea name="note_interne" id="st-note_interne" rows="3"
                placeholder="Ex : devis envoyé par WhatsApp, équipe de 2 personnes, motif du refus…"
                class="{{ $cls('note_interne', 'edit') }} resize-none">{{ $fromStatut ? old('note_interne') : '' }}</textarea>
            {!! $err('note_interne', 'edit') !!}
        </div>
    </x-admin.form.modifier-modal>

    {{-- ══ Modal — Voir une demande ══ --}}
    <x-admin.form.voir-modal id="modal-view-demande" subtitle="Détails de la demande" iconColor="violet"
        maxWidth="max-w-[520px]">
        @foreach ([
                'reference' => 'Référence',
                'nom' => 'Demandeur',
                'telephone' => 'Téléphone',
                'email' => 'E-mail',
                'organisation' => 'Organisation',
                'evenement' => 'Événement / projet',
                'lieu' => 'Lieu',
                'date' => 'Date',
                'services' => 'Services souhaités',
                'message' => 'Description du besoin',
                'devis' => 'Devis proposé',
                'statut' => 'Statut',
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

    {{-- ══ MODAL — Supprimer une demande ══ --}}
    <x-admin.form.supprimer-modal id="modal-delete-demande" title="Supprimer la demande" subtitleId="delete-modal-subtitle"
        subtitle="Une demande confirmée à venir doit d'abord être annulée." action="" formId="delete-demande-form"
        maxWidth="max-w-[420px]" />

    <script>
        document.addEventListener('turbo:load', function () {

            const dt = new DataTable({
                tableId: 'datatable-demandes',
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
                const modalId = 'modal-edit-demande';
                const action = `/admin/demandes-couverture/${data.id}`;

                document.getElementById('edit-demande-form').action = action;

                // Champs simples : id = edit-{nom}
                Object.entries(data).forEach(([key, value]) => {
                    const el = document.getElementById(`edit-${key}`);
                    if (el) el.value = value ?? '';
                });

                // Services (cases à cocher)
                document.querySelectorAll('#modal-edit-demande input[name="services[]"]').forEach(cb => {
                    cb.checked = (data.services || []).includes(cb.value);
                });

                document.getElementById('edit-modal-subtitle').textContent = data.nom;
                document.getElementById(`${modalId}-form-action`).value = action;
                document.getElementById(`${modalId}-modal-subtitle`).value = data.nom;

                openModal(modalId);
            };

            window.openStatutModal = (data) => {
                const modalId = 'modal-statut-demande';
                const action = `/admin/demandes-couverture/${data.id}/statut`;

                document.getElementById('statut-demande-form').action = action;

                document.getElementById('st-statut').value = data.statut ?? '';
                document.getElementById('st-montant').value = data.montant ?? '';
                document.getElementById('st-note_interne').value = data.note_interne ?? '';

                // Résumé de la demande
                document.getElementById('statut-resume-client').textContent =
                    `${data.reference ? data.reference + ' — ' : ''}${data.nom}`;
                document.getElementById('statut-resume-details').textContent = data.details ?? '';
                document.getElementById('statut-resume-evenement').textContent = data.evenement ?? '';
                document.getElementById('statut-resume-services').textContent = data.services ?? '';
                const msg = document.getElementById('statut-resume-message');
                msg.textContent = data.message ? `« ${data.message} »` : '';
                msg.classList.toggle('hidden', !data.message);

                document.getElementById('statut-modal-subtitle').textContent = data.nom;
                document.getElementById(`${modalId}-form-action`).value = action;
                document.getElementById(`${modalId}-modal-subtitle`).value = data.nom;

                openModal(modalId);
            };

            window.openDeleteModal = (id, name) => {
                const modalId = 'modal-delete-demande';
                const action = `/admin/demandes-couverture/${id}`;

                document.getElementById('delete-demande-form').action = action;
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
