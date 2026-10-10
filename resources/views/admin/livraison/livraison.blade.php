@extends('layouts.admin.app', ['title' => 'Livraisons'])

@section('content')
    @php
        $tabBase = 'inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-[12px] font-medium no-underline transition-colors border';
        $tabOn = 'bg-pslb-blue-800 text-white border-pslb-blue-800';
        $tabOff = 'bg-white dark:bg-pslb-blue-800 text-pslb-muted dark:text-pslb-blue-200 border-pslb-border dark:border-white/[.08] hover:text-pslb-blue-800 dark:hover:text-white';

        $statusBadge = [
            'en_attente' => 'bg-amber-500/10 text-amber-700 dark:text-amber-300',
            'confirmee' => 'bg-pslb-cyan-500/10 text-pslb-cyan-700 dark:text-pslb-cyan-300',
            'en_cours' => 'bg-blue-500/10 text-blue-700 dark:text-blue-300',
            'livree' => 'bg-emerald-500/10 text-emerald-700 dark:text-emerald-300',
            'refusee' => 'bg-red-500/10 text-red-600 dark:text-red-300',
        ];
    @endphp

    <x-admin.page-header title="Livraisons" description="Demandes de colis à livrer" :count="$livraisons->count()" count-label="demandes">
        <a href="{{ route('admin.livraisons.index') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-pslb-blue-800 hover:bg-pslb-blue-700 text-white text-[13px] font-medium cursor-pointer transition-colors border-0 no-underline">
            Actualiser
        </a>
    </x-admin.page-header>

    <div class="flex flex-wrap gap-1.5 mb-3">
        <a href="{{ route('admin.livraisons.index') }}" class="{{ $tabBase }} {{ empty($filtre) ? $tabOn : $tabOff }}">
            Tous <span class="opacity-70">{{ collect($counts)->sum() }}</span>
        </a>

        @foreach ($statuts as $value => $label)
            <a href="{{ route('admin.livraisons.index', ['statut' => $value]) }}"
                class="{{ $tabBase }} {{ ($filtre ?? null) === $value ? $tabOn : $tabOff }}">
                {{ $label }} <span class="opacity-70">{{ $counts[$value] ?? 0 }}</span>
            </a>
        @endforeach
    </div>

    <div class="bg-white dark:bg-pslb-blue-800 border border-pslb-border dark:border-white/[.08]
                rounded-xl shadow-sm p-3.5 mb-4 flex flex-col gap-2.5 sm:flex-row sm:items-center">
        <div class="flex w-full items-center gap-2.5 min-w-0 sm:flex-1">
            <div class="flex-1 min-w-0">
                <x-admin.table.search />
            </div>
            <div class="shrink-0">
                <x-admin.table.par-page-select />
            </div>
        </div>
        <span id="result-count" class="ml-auto text-[11.5px] text-pslb-muted dark:text-pslb-blue-300 shrink-0 sm:ml-0">
            {{ $livraisons->count() }}
        </span>
    </div>

    <x-admin.table.datatable :columns="[
            ['label' => 'CLIENT'],
            ['label' => 'COLIS'],
            ['label' => 'TRAJET'],
            ['label' => 'DATE'],
            ['label' => 'STATUT'],
            ['label' => 'ACTIONS'],
        ]" tableId="datatable-livraisons" item-label="livraisons">
        @foreach ($livraisons as $livraison)
            @php
                $statutLabel = $statuts[$livraison->statut] ?? ucfirst(str_replace('_', ' ', $livraison->statut));
                $trajet = trim(($livraison->ville_depart ?: '—') . ' → ' . ($livraison->ville_arrivee ?: '—'));
                $image = $livraison->image_colis ? asset('storage/' . $livraison->image_colis) : null;
            @endphp

            <tr class="tr-hov border-b border-pslb-border dark:border-white/[.08] table-row"
                data-name="{{ $livraison->nom }} {{ $livraison->telephone }} {{ $livraison->ville_depart }} {{ $livraison->ville_arrivee }} {{ $livraison->type_colis }}">
                <td class="px-4 py-[11px]">
                    <div class="flex items-center gap-[10px]">
                        <div class="w-[32px] h-[32px] rounded-full bg-pslb-cyan-500/15 flex items-center justify-center text-pslb-cyan-700 dark:text-pslb-cyan-300 font-display text-[10px] font-bold shrink-0">
                            {{ strtoupper(substr($livraison->nom, 0, 2)) }}
                        </div>
                        <div>
                            <p class="font-medium text-[12.5px] text-pslb-ink dark:text-pslb-blue-50 m-0">
                                {{ $livraison->nom }}
                            </p>
                            <p class="text-[10px] text-pslb-muted dark:text-pslb-blue-300 m-0">
                                {{ $livraison->telephone }}
                            </p>
                        </div>
                    </div>
                </td>

                <td class="px-3 py-[11px] text-[12.5px] text-pslb-ink dark:text-pslb-blue-50">
                    <div class="font-medium">{{ $livraison->type_colis ?? 'Colis' }}</div>
                    @if ($livraison->poids_kg)
                        <div class="text-[10px] text-pslb-muted dark:text-pslb-blue-300">{{ number_format($livraison->poids_kg, 2, ',', ' ') }} kg</div>
                    @endif
                </td>

                <td class="px-3 py-[11px] text-[12px] text-pslb-ink dark:text-pslb-blue-50 hidden md:table-cell">
                    <div>{{ $trajet }}</div>
                    @if ($livraison->adresse_livraison)
                        <div class="text-[10px] text-pslb-muted dark:text-pslb-blue-300">{{ Str::limit($livraison->adresse_livraison, 50) }}</div>
                    @endif
                </td>

                <td class="px-3 py-[11px] text-[12px] text-pslb-ink dark:text-pslb-blue-50 whitespace-nowrap">
                    {{ $livraison->date_livraison?->format('d/m/Y') ?? '—' }}
                    @if ($livraison->heure_livraison)
                        <span class="block text-[10px] text-pslb-muted dark:text-pslb-blue-300">{{ $livraison->heure_livraison }}</span>
                    @endif
                </td>

                <td class="px-3 py-[11px]">
                    <button type="button" onclick="openStatusModal({{ Js::from([
                        'id' => $livraison->id,
                        'nom' => $livraison->nom,
                        'statut' => $livraison->statut,
                        'note_interne' => $livraison->note_interne,
                        'type_colis' => $livraison->type_colis,
                        'ville_depart' => $livraison->ville_depart,
                        'ville_arrivee' => $livraison->ville_arrivee,
                        'description' => $livraison->description,
                    ]) }})"
                        class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full border-0 text-[11px] font-medium cursor-pointer transition-opacity hover:opacity-75 {{ $statusBadge[$livraison->statut] ?? 'bg-slate-500/10 text-slate-700' }}">
                        {{ $statutLabel }}
                        <svg class="w-[10px] h-[10px]" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                </td>

                <td class="px-4 py-[11px] text-right">
                    <div class="flex items-center justify-end gap-1.5">
                        <button type="button" onclick="openViewModal({{ Js::from([
                            'nom' => $livraison->nom,
                            'telephone' => $livraison->telephone,
                            'email' => $livraison->email ?: '—',
                            'type_colis' => $livraison->type_colis ?: '—',
                            'poids_kg' => $livraison->poids_kg ? number_format($livraison->poids_kg, 2, ',', ' ') . ' kg' : '—',
                            'ville_depart' => $livraison->ville_depart ?: '—',
                            'adresse_depart' => $livraison->adresse_depart ?: '—',
                            'ville_arrivee' => $livraison->ville_arrivee ?: '—',
                            'adresse_livraison' => $livraison->adresse_livraison ?: '—',
                            'date_livraison' => $livraison->date_livraison?->format('d/m/Y') ?? '—',
                            'heure_livraison' => $livraison->heure_livraison ?: '—',
                            'description' => $livraison->description ?: '—',
                            'statut' => $statutLabel,
                            'image' => $image,
                        ]) }})" class="w-[28px] h-[28px] rounded-lg border border-pslb-border dark:border-white/[.10] bg-pslb-surface dark:bg-pslb-blue-900 text-pslb-muted hover:text-pslb-cyan-600 dark:hover:text-pslb-cyan-300 hover:border-pslb-cyan-500/50 flex items-center justify-center cursor-pointer transition-colors" title="Voir la demande">
                            <svg class="w-[13px] h-[13px]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12Z" />
                                <circle cx="12" cy="12" r="3" />
                            </svg>
                        </button>

                        <x-admin.ux.button.supprimer-modal onclick="openDeleteModal({{ $livraison->id }}, {{ Js::from($livraison->nom) }})" />
                    </div>
                </td>
            </tr>
        @endforeach
    </x-admin.table.datatable>

    <x-admin.form.voir-modal id="modal-view-livraison" subtitle="Détails de la demande" iconColor="cyan" maxWidth="max-w-[560px]">
        <div id="view-livraison-image" class="hidden mb-3 overflow-hidden rounded-xl border border-pslb-border dark:border-white/[.08] bg-slate-50 dark:bg-slate-900">
            <img id="livraison-image-preview" src="" alt="Image du colis" class="w-full h-52 object-cover">
        </div>

        @foreach ([
            'nom' => 'Client',
            'telephone' => 'Téléphone',
            'email' => 'E-mail',
            'type_colis' => 'Type de colis',
            'poids_kg' => 'Poids',
            'ville_depart' => 'Ville de départ',
            'adresse_depart' => 'Adresse de départ',
            'ville_arrivee' => 'Ville d’arrivée',
            'adresse_livraison' => 'Adresse de livraison',
            'date_livraison' => 'Date prévue',
            'heure_livraison' => 'Heure prévue',
            'description' => 'Description',
            'statut' => 'Statut',
        ] as $key => $label)
            <div>
                <p class="text-[11px] font-medium text-pslb-muted dark:text-pslb-blue-300 uppercase tracking-[.06em] mb-1">{{ $label }}</p>
                <p id="view-{{ $key }}" class="text-[12.5px] text-pslb-ink dark:text-pslb-blue-50 m-0 leading-relaxed">—</p>
            </div>

            @if (!$loop->last)
                <div class="border-t border-pslb-border dark:border-white/[.08]"></div>
            @endif
        @endforeach
    </x-admin.form.voir-modal>

    <x-admin.form.modifier-modal id="modal-statut-livraison" title="Mettre à jour la livraison" subtitleId="status-modal-subtitle"
        subtitle="—" action="" formId="status-livraison-form" method="PATCH" errorBag="edit" submitLabel="Enregistrer"
        submitColor="bg-pslb-blue-800 hover:bg-pslb-blue-700" maxWidth="max-w-[460px]">

        <div class="p-3 rounded-lg bg-pslb-surface dark:bg-pslb-blue-900 border border-pslb-border dark:border-white/[.08]">
            <p id="status-resume-client" class="text-[12.5px] font-medium text-pslb-ink dark:text-pslb-blue-50 m-0">—</p>
            <p id="status-resume-trajet" class="text-[11px] text-pslb-muted dark:text-pslb-blue-300 mt-0.5 m-0">—</p>
            <p id="status-resume-description" class="text-[11.5px] italic text-pslb-muted dark:text-pslb-blue-200 mt-2 m-0 leading-relaxed">—</p>
        </div>

        <div>
            <label class="block text-[11.5px] font-medium text-pslb-muted dark:text-pslb-blue-200 mb-1.5">Statut <span class="text-red-500">*</span></label>
            <select name="statut" id="status-statut" class="w-full bg-pslb-surface dark:bg-pslb-blue-900 border border-pslb-border dark:border-white/[.08] rounded-lg py-[8px] px-3 text-[12.5px] text-pslb-ink dark:text-pslb-blue-50 outline-none transition-colors focus:border-pslb-cyan-500 cursor-pointer" required>
                @foreach ($statuts as $value => $label)
                    <option value="{{ $value }}">{{ $label }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-[11.5px] font-medium text-pslb-muted dark:text-pslb-blue-200 mb-1.5">Note interne</label>
            <textarea name="note_interne" id="status-note-interne" rows="4" class="w-full bg-pslb-surface dark:bg-pslb-blue-900 border border-pslb-border dark:border-white/[.08] rounded-lg py-[8px] px-3 text-[12.5px] text-pslb-ink dark:text-pslb-blue-50 outline-none transition-colors placeholder:text-pslb-muted focus:border-pslb-cyan-500 resize-none"></textarea>
        </div>
    </x-admin.form.modifier-modal>

    <x-admin.form.supprimer-modal id="modal-delete-livraison" title="Supprimer la demande" subtitleId="delete-modal-subtitle"
        subtitle="Cette action est irréversible." action="" formId="delete-livraison-form"
        maxWidth="max-w-[420px]" />

    <script>
        document.addEventListener('turbo:load', function () {
            const dt = new DataTable({
                tableId: 'datatable-livraisons',
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

            const filterPerPageSelect = initFormationSelect('filter-per-page-select');

            window.openViewModal = (data) => {
                const fields = [
                    'nom', 'telephone', 'email', 'type_colis', 'poids_kg', 'ville_depart', 'adresse_depart',
                    'ville_arrivee', 'adresse_livraison', 'date_livraison', 'heure_livraison', 'description', 'statut'
                ];

                fields.forEach((key) => {
                    const el = document.getElementById('view-' + key);
                    if (el) {
                        el.textContent = (data[key] !== null && data[key] !== undefined && data[key] !== '') ? data[key] : '—';
                    }
                });

                const imgWrap = document.getElementById('view-livraison-image');
                const img = document.getElementById('livraison-image-preview');

                if (data.image) {
                    img.src = data.image;
                    imgWrap.classList.remove('hidden');
                } else {
                    img.src = '';
                    imgWrap.classList.add('hidden');
                }

                openModal('modal-view-livraison');
            };

            window.openStatusModal = (data) => {
                const form = document.getElementById('status-livraison-form');
                form.action = '/admin/livraisons/' + data.id + '/statut';
                document.getElementById('status-modal-subtitle').textContent = data.nom;
                document.getElementById('status-statut').value = data.statut;
                document.getElementById('status-note-interne').value = data.note_interne || '';
                document.getElementById('status-resume-client').textContent = data.nom;
                document.getElementById('status-resume-trajet').textContent = (data.ville_depart || '—') + ' → ' + (data.ville_arrivee || '—');
                document.getElementById('status-resume-description').textContent = data.description || 'Aucune description.';
                openModal('modal-statut-livraison');
            };

            window.openDeleteModal = (id, name) => {
                const form = document.getElementById('delete-livraison-form');
                form.action = '/admin/livraisons/' + id;
                document.getElementById('delete-modal-subtitle').textContent = name;
                openModal('modal-delete-livraison');
            };
        });
    </script>
@endsection
