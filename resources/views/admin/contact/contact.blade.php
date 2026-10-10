@extends('layouts.admin.app', ['title' => 'Messages de contact'])

@section('content')
    <style>
        #filter-per-page-select+.ts-wrapper {
            min-width: 160px;
            max-width: 220px;
            width: auto;
        }
    </style>

    @php
        $sujets = App\Models\ContactMessage::SUJETS;

        $tabBase = 'inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-[12px] font-medium no-underline transition-colors border';
        $tabOn = 'bg-pslb-blue-800 text-white border-pslb-blue-800';
        $tabOff = 'bg-white dark:bg-pslb-blue-800 text-pslb-muted dark:text-pslb-blue-200 border-pslb-border dark:border-white/[.08] hover:text-pslb-blue-800 dark:hover:text-white';

        $statusBadge = [
            true => 'bg-pslb-cyan-500/10 text-pslb-cyan-700 dark:text-pslb-cyan-300',
            false => 'bg-pslb-muted/10 text-pslb-muted dark:text-pslb-blue-200',
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

    {{-- En-tête de page --}}
    <x-admin.page-header title="Messages de contact" description="Messages reçus depuis le formulaire de contact du site"
        :count="$messages->count()" count-label="messages">
        <a href="{{ route('admin.contacts.index') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-pslb-blue-800 hover:bg-pslb-blue-700 text-white text-[13px] font-medium cursor-pointer transition-colors border-0 no-underline">
            Actualiser
        </a>
    </x-admin.page-header>

    {{-- Onglets de filtres --}}
    <div class="flex flex-wrap gap-1.5 mb-3">
        <a href="{{ route('admin.contacts.index') }}" class="{{ $tabBase }} {{ empty($filtre) ? $tabOn : $tabOff }}">
            Tous <span class="opacity-70">{{ collect($counts)->sum() }}</span>
        </a>

        <a href="{{ route('admin.contacts.index', ['lu' => 0]) }}" class="{{ $tabBase }} {{ ($filtre ?? null) === 'non_lus' ? $tabOn : $tabOff }}">
            Non lus <span class="opacity-70">{{ $counts['non_lus'] ?? 0 }}</span>
        </a>

        <a href="{{ route('admin.contacts.index', ['lu' => 1]) }}" class="{{ $tabBase }} {{ ($filtre ?? null) === 'lus' ? $tabOn : $tabOff }}">
            Lus <span class="opacity-70">{{ $counts['lus'] ?? 0 }}</span>
        </a>

        @foreach ($sujets as $value => $label)
            <a href="{{ route('admin.contacts.index', ['sujet' => $value]) }}"
                class="{{ $tabBase }} {{ ($filtre ?? null) === $value ? $tabOn : $tabOff }}">
                {{ $label }} <span class="opacity-70">{{ $counts[$value] ?? 0 }}</span>
            </a>
        @endforeach
    </div>

    {{-- Barre de filtre --}}
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
            {{ $messages->count() }}
        </span>
    </div>

    {{-- Tableau --}}
    <x-admin.table.datatable :columns="[
            ['label' => 'EXPÉDITEUR'],
            ['label' => 'SUJET'],
            ['label' => 'CONTACT'],
            ['label' => 'MESSAGE'],
            ['label' => 'DATE'],
            ['label' => 'STATUT'],
            ['label' => 'ACTIONS'],
        ]" tableId="datatable-contact" item-label="messages">
        @foreach ($messages as $message)
            @php
                $libelleSujet = $sujets[$message->sujet] ?? ucfirst((string) $message->sujet);
                $statusLabel = $message->est_lu ? 'Lu' : 'Non lu';
                $preview = \Illuminate\Support\Str::limit(strip_tags($message->message), 90, '...');
                $detailData = [
                    'id' => $message->id,
                    'nom' => $message->nom,
                    'telephone' => $message->telephone,
                    'email' => $message->email ?: '—',
                    'sujet' => $libelleSujet,
                    'message' => $message->message ?: '—',
                    'ip_address' => $message->ip_address ?: '—',
                    'statut' => $statusLabel,
                    'recu_le' => $message->created_at?->format('d/m/Y à H:i') ?? '—',
                ];
            @endphp

            <tr class="tr-hov border-b border-pslb-border dark:border-white/[.08] table-row"
                data-name="{{ $message->nom }} {{ $message->telephone }} {{ $message->email }} {{ $libelleSujet }}">
                <td class="px-4 py-[11px]">
                    <div class="flex items-center gap-[10px]">
                        <div class="w-[32px] h-[32px] rounded-full bg-pslb-cyan-500/15 flex items-center justify-center text-pslb-cyan-700 dark:text-pslb-cyan-300 font-display text-[10px] font-bold shrink-0">
                            {{ strtoupper(substr($message->nom, 0, 2)) }}
                        </div>
                        <div>
                            <p class="font-medium text-[12.5px] text-pslb-ink dark:text-pslb-blue-50 m-0">
                                {{ $message->nom }}
                            </p>
                            <p class="text-[10px] text-pslb-muted dark:text-pslb-blue-300 m-0">
                                {{ $message->created_at?->format('d/m/Y') ?? '—' }}
                            </p>
                        </div>
                    </div>
                </td>

                <td class="px-3 py-[11px] text-[12.5px] text-pslb-ink dark:text-pslb-blue-50">
                    <span class="inline-flex items-center px-2 py-1 rounded-full bg-pslb-blue-50 text-pslb-blue-700 dark:bg-pslb-blue-700 dark:text-pslb-blue-50 text-[10px] font-medium">
                        {{ $libelleSujet }}
                    </span>
                </td>

                <td class="px-3 py-[11px] text-[12px] text-pslb-ink dark:text-pslb-blue-50 hidden md:table-cell">
                    <div>{{ $message->telephone }}</div>
                    @if ($message->email)
                        <div class="text-[10px] text-pslb-muted dark:text-pslb-blue-300">{{ $message->email }}</div>
                    @endif
                </td>

                <td class="px-3 py-[11px] text-[12.2px] text-pslb-ink dark:text-pslb-blue-50 max-w-[240px]">
                    {{ $preview }}
                </td>

                <td class="px-3 py-[11px] text-[12px] text-pslb-ink dark:text-pslb-blue-50 whitespace-nowrap">
                    {{ $message->created_at?->format('d/m/Y à H:i') ?? '—' }}
                </td>

                <td class="px-3 py-[11px]">
                    <button type="button" onclick="openStatusModal({{ Js::from($detailData) }})"
                        class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full border-0 text-[11px] font-medium cursor-pointer transition-opacity hover:opacity-75 {{ $statusBadge[$message->est_lu ? 1 : 0] }}">
                        {{ $statusLabel }}
                        <svg class="w-[10px] h-[10px]" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                </td>

                <td class="px-4 py-[11px] text-right">
                    <div class="flex items-center justify-end gap-1.5">
                        <x-admin.ux.button.voir-modal modal="modal-view-message" subtitle="{{ $message->nom }}"
                            tip="Voir le message" :fields="[
                                'nom' => $message->nom,
                                'telephone' => $message->telephone,
                                'email' => $message->email ?: '—',
                                'sujet' => $libelleSujet,
                                'message' => $message->message ?: '—',
                                'ip_address' => $message->ip_address ?: '—',
                                'statut' => $statusLabel,
                                'recu_le' => $message->created_at?->format('d/m/Y à H:i') ?? '—',
                            ]" />

                        <x-admin.ux.button.supprimer-modal
                            onclick="openDeleteModal({{ $message->id }}, {{ Js::from($message->nom) }})" />
                    </div>
                </td>
            </tr>
        @endforeach
    </x-admin.table.datatable>

    {{-- Modal — Voir le message --}}
    <x-admin.form.voir-modal id="modal-view-message" subtitle="Détails du message" iconColor="cyan"
        maxWidth="max-w-[520px]">
        @foreach ([
                'nom' => 'Nom',
                'telephone' => 'Téléphone',
                'email' => 'E-mail',
                'sujet' => 'Sujet',
                'message' => 'Message',
                'ip_address' => 'Adresse IP',
                'statut' => 'Statut',
                'recu_le' => 'Reçu le',
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

    {{-- Modal — Changer le statut --}}
    <x-admin.form.modifier-modal id="modal-statut-message" title="Mettre à jour le message" subtitleId="status-modal-subtitle"
        subtitle="—" action="" formId="status-message-form" method="PATCH" errorBag="edit" submitLabel="Enregistrer"
        submitColor="bg-pslb-blue-800 hover:bg-pslb-blue-700" maxWidth="max-w-[460px]">

        <div class="p-3 rounded-lg bg-pslb-surface dark:bg-pslb-blue-900 border border-pslb-border dark:border-white/[.08]">
            <p id="status-resume-client" class="text-[12.5px] font-medium text-pslb-ink dark:text-pslb-blue-50 m-0">—</p>
            <p id="status-resume-sujet" class="text-[11px] text-pslb-muted dark:text-pslb-blue-300 mt-0.5 m-0">—</p>
            <p id="status-resume-message" class="text-[11.5px] italic text-pslb-muted dark:text-pslb-blue-200 mt-2 m-0 leading-relaxed">—</p>
        </div>

        <div>
            <label class="{{ $lbl }}">Statut <span class="text-red-500">*</span></label>
            <select name="est_lu" id="status-est-lu" class="{{ $cls('est_lu', 'edit') }} cursor-pointer" required>
                <option value="0">Non lu</option>
                <option value="1">Lu</option>
            </select>
            {!! $err('est_lu', 'edit') !!}
        </div>
    </x-admin.form.modifier-modal>

    {{-- Modal — Supprimer --}}
    <x-admin.form.supprimer-modal id="modal-delete-message" title="Supprimer le message" subtitleId="delete-modal-subtitle"
        subtitle="Cette action est irréversible." action="" formId="delete-message-form"
        maxWidth="max-w-[420px]" />

    <script>
        document.addEventListener('turbo:load', function () {
            const dt = new DataTable({
                tableId: 'datatable-contact',
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
                const modalId = 'modal-statut-message';
                const action = `/admin/contacts/${data.id}/statut`;

                document.getElementById('status-message-form').action = action;
                document.getElementById('status-modal-subtitle').textContent = data.nom;
                document.getElementById('status-est-lu').value = data.statut === 'Lu' ? '1' : '0';
                document.getElementById('status-resume-client').textContent = data.nom;
                document.getElementById('status-resume-sujet').textContent = data.sujet;
                document.getElementById('status-resume-message').textContent = data.message;

                openModal(modalId);
            };

            window.openDeleteModal = (id, name) => {
                const form = document.getElementById('delete-message-form');
                form.action = `/admin/contacts/${id}`;
                document.getElementById('delete-modal-subtitle').textContent = name;
                openModal('modal-delete-message');
            };
        });
    </script>
@endsection
