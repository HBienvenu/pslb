@extends('layouts.admin.app', ['title' => 'Demandes PSLB Digital'])

@section('content')
    @php
        $statusBadge = [
            'en_attente' => 'bg-amber-500/10 text-amber-700 dark:text-amber-300',
            'en_cours' => 'bg-cyan-500/10 text-cyan-700 dark:text-cyan-300',
            'traite' => 'bg-emerald-500/10 text-emerald-700 dark:text-emerald-300',
            'valide' => 'bg-pslb-cyan-500/10 text-pslb-cyan-700 dark:text-pslb-cyan-300',
            'refuse' => 'bg-red-500/10 text-red-700 dark:text-red-300',
        ];

        $lbl = 'block text-[11.5px] font-medium text-pslb-muted dark:text-pslb-blue-200 mb-1.5';
        $base = 'w-full bg-pslb-surface dark:bg-pslb-blue-900 border rounded-lg py-[8px] px-3 text-[12.5px] text-pslb-ink dark:text-pslb-blue-50 outline-none transition-colors placeholder:text-pslb-muted';
        $cls = fn($name, $bag = 'default') => $base . ' ' . ($errors->getBag($bag)->has($name)
            ? 'border-red-400 dark:border-red-500 focus:border-red-500'
            : 'border-pslb-border dark:border-white/[.08] focus:border-pslb-cyan-500');
        $err = fn($name, $bag = 'default') => $errors->getBag($bag)->has($name)
            ? '<p class="mt-1.5 text-[11px] text-red-500 dark:text-red-400">' . e($errors->getBag($bag)->first($name)) . '</p>'
            : '';
    @endphp

    <x-admin.page-header title="Demandes PSLB Digital" description="Demandes reçues depuis le volet formation &amp; services digitaux" :count="$demandes->count()" count-label="demandes">
        <a href="{{ route('admin.demandes-formation.index') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-pslb-blue-800 hover:bg-pslb-blue-700 text-white text-[13px] font-medium cursor-pointer transition-colors border-0 no-underline">
            Actualiser
        </a>
    </x-admin.page-header>

    <div class="mb-4 flex flex-wrap gap-1.5">
        <a href="{{ route('admin.demandes-formation.index') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-[12px] font-medium border {{ empty($filtre) ? 'bg-pslb-blue-800 text-white border-pslb-blue-800' : 'bg-white dark:bg-pslb-blue-800 text-pslb-muted dark:text-pslb-blue-200 border-pslb-border dark:border-white/[.08]' }}">
            Tous <span class="opacity-70">{{ collect($counts)->sum() }}</span>
        </a>
        @foreach ($statuts as $key => $label)
            <a href="{{ route('admin.demandes-formation.index', ['statut' => $key]) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-[12px] font-medium border {{ ($filtre ?? null) === $key ? 'bg-pslb-blue-800 text-white border-pslb-blue-800' : 'bg-white dark:bg-pslb-blue-800 text-pslb-muted dark:text-pslb-blue-200 border-pslb-border dark:border-white/[.08]' }}">
                {{ $label }} <span class="opacity-70">{{ $counts[$key] ?? 0 }}</span>
            </a>
        @endforeach
    </div>

    <div class="bg-white dark:bg-pslb-blue-800 border border-pslb-border dark:border-white/[.08] rounded-xl shadow-sm p-3.5 mb-4 flex flex-col gap-2.5 sm:flex-row sm:items-center">
        <div class="flex w-full items-center gap-2.5 min-w-0 sm:flex-1">
            <div class="flex-1 min-w-0">
                <x-admin.table.search />
            </div>
            <div class="shrink-0">
                <x-admin.table.par-page-select />
            </div>
        </div>
        <span id="result-count" class="ml-auto text-[11.5px] text-pslb-muted dark:text-pslb-blue-300 shrink-0 sm:ml-0">
            {{ $demandes->count() }}
        </span>
    </div>

    <x-admin.table.datatable :columns="[['label' => 'CLIENT'], ['label' => 'SERVICE'], ['label' => 'CONTACT'], ['label' => 'BUDGET'], ['label' => 'DATE'], ['label' => 'STATUT'], ['label' => 'ACTIONS'] ]" tableId="datatable-formation" item-label="demandes">
        @foreach ($demandes as $demande)
            @php
                $serviceLabel = App\Models\DemandeFormation::SERVICES[$demande->service] ?? ucfirst((string) $demande->service);
                $detailData = [
                    'id' => $demande->id,
                    'nom' => $demande->nom,
                    'telephone' => $demande->telephone,
                    'email' => $demande->email ?: '—',
                    'service' => $serviceLabel,
                    'budget' => $demande->budget ? number_format((float) $demande->budget, 0, ',', ' ') . ' F CFA' : '—',
                    'date_prevue' => $demande->date_prevue?->format('d/m/Y') ?? '—',
                    'description' => $demande->description ?: '—',
                    'statut' => $statuts[$demande->statut] ?? ucfirst(str_replace('_', ' ', $demande->statut)),
                    'note_interne' => $demande->note_interne ?: '—',
                    'recu_le' => $demande->created_at?->format('d/m/Y à H:i') ?? '—',
                ];
            @endphp

            <tr class="tr-hov border-b border-pslb-border dark:border-white/[.08] table-row" data-name="{{ $demande->nom }} {{ $demande->telephone }} {{ $demande->email }} {{ $serviceLabel }}">
                <td class="px-4 py-[11px]">
                    <div class="flex items-center gap-[10px]">
                        <div class="w-[32px] h-[32px] rounded-full bg-pslb-cyan-500/15 flex items-center justify-center text-pslb-cyan-700 dark:text-pslb-cyan-300 font-display text-[10px] font-bold shrink-0">
                            {{ strtoupper(substr($demande->nom, 0, 2)) }}
                        </div>
                        <div>
                            <p class="font-medium text-[12.5px] text-pslb-ink dark:text-pslb-blue-50 m-0">{{ $demande->nom }}</p>
                            <p class="text-[10px] text-pslb-muted dark:text-pslb-blue-300 m-0">{{ $demande->reference ?? '—' }}</p>
                        </div>
                    </div>
                </td>

                <td class="px-3 py-[11px] text-[12.5px] text-pslb-ink dark:text-pslb-blue-50">
                    <span class="inline-flex items-center px-2 py-1 rounded-full bg-pslb-blue-50 text-pslb-blue-700 dark:bg-pslb-blue-700 dark:text-pslb-blue-50 text-[10px] font-medium">
                        {{ $serviceLabel }}
                    </span>
                </td>

                <td class="px-3 py-[11px] text-[12px] text-pslb-ink dark:text-pslb-blue-50 hidden md:table-cell">
                    <div>{{ $demande->telephone }}</div>
                    @if ($demande->email)
                        <div class="text-[10px] text-pslb-muted dark:text-pslb-blue-300">{{ $demande->email }}</div>
                    @endif
                </td>

                <td class="px-3 py-[11px] text-[12px] text-pslb-ink dark:text-pslb-blue-50 whitespace-nowrap">
                    {{ $demande->budget ? number_format((float) $demande->budget, 0, ',', ' ') . ' F CFA' : '—' }}
                </td>

                <td class="px-3 py-[11px] text-[12px] text-pslb-ink dark:text-pslb-blue-50 whitespace-nowrap">
                    {{ $demande->date_prevue?->format('d/m/Y') ?? '—' }}
                </td>

                <td class="px-3 py-[11px]">
                    <button type="button" onclick="openStatusModal({{ Js::from($detailData) }})" class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full border-0 text-[11px] font-medium cursor-pointer transition-opacity hover:opacity-75 {{ $statusBadge[$demande->statut] ?? 'bg-slate-500/10 text-slate-700 dark:text-slate-300' }}">
                        {{ $statuts[$demande->statut] ?? 'Inconnu' }}
                        <svg class="w-[10px] h-[10px]" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M19 9l-7 7-7-7" /></svg>
                    </button>
                </td>

                <td class="px-4 py-[11px] text-right">
                    <div class="flex items-center justify-end gap-1.5">
                        <x-admin.ux.button.voir-modal modal="modal-view-formation" subtitle="{{ $demande->nom }}" tip="Voir la demande" :fields="[
                            'nom' => $demande->nom,
                            'telephone' => $demande->telephone,
                            'email' => $demande->email ?: '—',
                            'service' => $serviceLabel,
                            'budget' => $demande->budget ? number_format((float) $demande->budget, 0, ',', ' ') . ' F CFA' : '—',
                            'date_prevue' => $demande->date_prevue?->format('d/m/Y') ?? '—',
                            'description' => $demande->description ?: '—',
                            'statut' => $statuts[$demande->statut] ?? ucfirst(str_replace('_', ' ', $demande->statut)),
                            'note_interne' => $demande->note_interne ?: '—',
                            'recu_le' => $demande->created_at?->format('d/m/Y à H:i') ?? '—',
                        ]" />

                        <x-admin.ux.button.supprimer-modal onclick="openDeleteModal({{ $demande->id }}, {{ Js::from($demande->nom) }})" />
                    </div>
                </td>
            </tr>
        @endforeach
    </x-admin.table.datatable>

    <x-admin.form.voir-modal id="modal-view-formation" subtitle="Détails de la demande" iconColor="cyan" maxWidth="max-w-[520px]">
        @foreach (['nom' => 'Nom', 'telephone' => 'Téléphone', 'email' => 'E-mail', 'service' => 'Service', 'budget' => 'Budget', 'date_prevue' => 'Date prévue', 'description' => 'Description', 'statut' => 'Statut', 'note_interne' => 'Note interne', 'recu_le' => 'Reçu le'] as $key => $label)
            <div>
                <p class="text-[11px] font-medium text-pslb-muted dark:text-pslb-blue-300 uppercase tracking-[.06em] mb-1">{{ $label }}</p>
                <p id="view-{{ $key }}" class="text-[12.5px] text-pslb-ink dark:text-pslb-blue-50 m-0 leading-relaxed">—</p>
            </div>
            @if (!$loop->last)
                <div class="border-t border-pslb-border dark:border-white/[.08]"></div>
            @endif
        @endforeach
    </x-admin.form.voir-modal>

    <x-admin.form.modifier-modal id="modal-statut-formation" title="Mettre à jour la demande" subtitleId="status-modal-subtitle" subtitle="—" action="" formId="status-formation-form" method="PATCH" errorBag="edit" submitLabel="Enregistrer" submitColor="bg-pslb-blue-800 hover:bg-pslb-blue-700" maxWidth="max-w-[460px]">
        <div class="p-3 rounded-lg bg-pslb-surface dark:bg-pslb-blue-900 border border-pslb-border dark:border-white/[.08]">
            <p id="status-resume-client" class="text-[12.5px] font-medium text-pslb-ink dark:text-pslb-blue-50 m-0">—</p>
            <p id="status-resume-service" class="text-[11px] text-pslb-muted dark:text-pslb-blue-300 mt-0.5 m-0">—</p>
            <p id="status-resume-description" class="text-[11.5px] italic text-pslb-muted dark:text-pslb-blue-200 mt-2 m-0 leading-relaxed">—</p>
        </div>

        <div>
            <label class="{{ $lbl }}">Statut <span class="text-red-500">*</span></label>
            <select name="statut" id="status-statut" class="{{ $cls('statut', 'edit') }} cursor-pointer" required>
                @foreach ($statuts as $key => $label)
                    <option value="{{ $key }}">{{ $label }}</option>
                @endforeach
            </select>
            {!! $err('statut', 'edit') !!}
        </div>

        <div>
            <label class="{{ $lbl }}">Budget estimé</label>
            <input type="number" min="0" step="1000" name="budget" id="status-budget" class="{{ $cls('budget', 'edit') }}" placeholder="0">
            {!! $err('budget', 'edit') !!}
        </div>

        <div>
            <label class="{{ $lbl }}">Note interne</label>
            <textarea name="note_interne" id="status-note" rows="3" class="{{ $cls('note_interne', 'edit') }}" placeholder="Ajoutez une note..."></textarea>
            {!! $err('note_interne', 'edit') !!}
        </div>
    </x-admin.form.modifier-modal>

    <x-admin.form.supprimer-modal id="modal-delete-formation" title="Supprimer la demande" subtitleId="delete-modal-subtitle" subtitle="Cette action est irréversible." action="" formId="delete-formation-form" maxWidth="max-w-[420px]" />

    <script>
        document.addEventListener('turbo:load', function () {
            const dt = new DataTable({
                tableId: 'datatable-formation',
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

            window.openStatusModal = (data) => {
                const modalId = 'modal-statut-formation';
                const action = `/admin/demandes-formation/${data.id}/statut`;

                document.getElementById('status-formation-form').action = action;
                document.getElementById('status-modal-subtitle').textContent = data.nom;
                document.getElementById('status-statut').value = Object.keys(@json($statuts)).find(key => @json($statuts)[key] === data.statut) ?? 'en_attente';
                document.getElementById('status-budget').value = data.budget && data.budget !== '—' ? data.budget.replace(/\D/g, '') : '';
                document.getElementById('status-note').value = '';
                document.getElementById('status-resume-client').textContent = data.nom;
                document.getElementById('status-resume-service').textContent = data.service;
                document.getElementById('status-resume-description').textContent = data.description;

                openModal(modalId);
            };

            window.openDeleteModal = (id, name) => {
                const form = document.getElementById('delete-formation-form');
                form.action = `/admin/demandes-formation/${id}`;
                document.getElementById('delete-modal-subtitle').textContent = name;
                openModal('modal-delete-formation');
            };
        });
    </script>
@endsection
