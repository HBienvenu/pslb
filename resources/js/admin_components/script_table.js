/**
 * DataTable — script global réutilisable
 * ─────────────────────────────────────────────────────────────
 * Usage minimal sur n'importe quelle page :
 *
 *   new DataTable({
 *     tableId  : 'mon-tableau',   // id du <table>
 *     tbodyId  : 'mon-tbody',     // id du <tbody>
 *     rowClass : 'mon-row',       // classe sur chaque <tr>
 *     searchId : 'mon-search',    // input recherche
 *     perPageId: 'mon-per-page',  // select lignes/page
 *     countId  : 'mon-count',     // span compteur résultats
 *     pagFromId: 'mon-pag-from',  // span "de"
 *     pagToId  : 'mon-pag-to',    // span "à"
 *     pagTotalId:'mon-pag-total', // span "sur X"
 *     pagBtnsId: 'mon-pag-btns',  // div boutons pagination
 *     emptyId  : 'mon-empty',     // div empty state
 *   })
 */
window.DataTable = class DataTable {
    constructor(cfg) {
        this.cfg = cfg;

        // Éléments DOM
        this.table = document.getElementById(cfg.tableId);
        this.tbody = document.getElementById(cfg.tbodyId);
        if (!this.table || !this.tbody) return;

        this.allRows = Array.from(
            document.querySelectorAll("." + cfg.rowClass),
        );

        // Contrôles
        this.$ = (id) => document.getElementById(id);

        // État
        this.filtered = [...this.allRows];
        this.currentPage = 1;
        this.perPage = parseInt(this.$(cfg.perPageId)?.value || 10);
        this.sortCol = -1;
        this.sortDir = "asc";

        // Hook filtres externes (role, statut, etc.)
        // Format : [{ el: selectElement, attr: 'data-attribute' }]
        this._extraFilters = [];

        this._bindBase();
        this._bindSorting();
        this.refresh();
    }

    // ─────────────────────────────────────────────────────────
    // API publique
    // ─────────────────────────────────────────────────────────

    /**
     * Enregistrer un filtre externe
     * @param {HTMLElement} el    — l'élément select/input
     * @param {string}      attr  — le data-attribute sur le <tr> (ex: 'role', 'status')
     */
    addFilter(el, attr, matcher = null) {
        if (!el) return;
        this._extraFilters.push({ el, attr, matcher });
        el.addEventListener("change", () => {
            this.currentPage = 1;
            this.refresh();
        });
    }

    /** Forcer un recalcul depuis l'extérieur */
    refresh() {
        this._applyFilters();
    }

    // ─────────────────────────────────────────────────────────
    // Interne
    // ─────────────────────────────────────────────────────────

    _bindBase() {
        this.$(this.cfg.searchId)?.addEventListener("input", () => {
            this.currentPage = 1;
            this._applyFilters();
        });
        this.$(this.cfg.perPageId)?.addEventListener("change", (e) => {
            this.perPage = parseInt(e.target.value);
            this.currentPage = 1;
            this._render();
        });
    }

    _bindSorting() {
        this.table.querySelectorAll("thead th[data-sort]").forEach((th) => {
            th.style.cursor = "pointer";
            th.title = "Cliquer pour trier";
            th.addEventListener("click", () => {
                const attr = th.dataset.sort;
                this.sortDir =
                    this.sortCol === th && this.sortDir === "asc"
                        ? "desc"
                        : "asc";
                this.sortCol = th;

                // Icônes
                this.table
                    .querySelectorAll("thead th .sort-icon")
                    .forEach((i) => i.remove());
                const ic = document.createElement("span");
                ic.className = "sort-icon ml-1 text-[9px] opacity-60";
                ic.textContent = this.sortDir === "asc" ? "▲" : "▼";
                th.appendChild(ic);

                this.filtered.sort((a, b) => {
                    const av = (a.dataset[attr] || "").toLowerCase();
                    const bv = (b.dataset[attr] || "").toLowerCase();
                    return this.sortDir === "asc"
                        ? av < bv
                            ? -1
                            : av > bv
                              ? 1
                              : 0
                        : av > bv
                          ? -1
                          : av < bv
                            ? 1
                            : 0;
                });
                this.currentPage = 1;
                this._render();
            });
        });
    }

    _applyFilters() {
        const q = (this.$(this.cfg.searchId)?.value || "").toLowerCase().trim();

        this.filtered = this.allRows.filter((row) => {
            const matchSearch = !q || row.innerText.toLowerCase().includes(q);

            const matchExtra = this._extraFilters.every((f) => {
                const val = f.el.value;
                if (!val) return true;
                // Matcher personnalisé si fourni, sinon comparaison stricte
                if (f.matcher) return f.matcher(row, val);
                return row.dataset[f.attr] === val;
            });

            return matchSearch && matchExtra;
        });

        this._render();
    }

    _render() {
        const total = this.filtered.length;
        const pp = this.perPage === -1 ? total : this.perPage;
        const pages = Math.max(1, Math.ceil(total / (pp || 1)));

        if (this.currentPage > pages) this.currentPage = pages;

        const start = (this.currentPage - 1) * pp;
        const end = Math.min(start + pp, total);

        // Show / hide + réordonnancement sans toucher le HTML interne
        this.allRows.forEach((r) => (r.style.display = "none"));
        this.filtered.slice(start, end).forEach((r) => {
            r.style.display = "";
            this.tbody.appendChild(r);
        });

        // Empty state
        const empty = this.$(this.cfg.emptyId);
        empty?.classList.toggle("hidden", total > 0);

        // Compteurs
        const from = total === 0 ? 0 : start + 1;
        const s = (id, val) => {
            const el = this.$(id);
            if (el) el.textContent = val;
        };
        s(this.cfg.countId, total + " résultat" + (total > 1 ? "s" : ""));
        s(this.cfg.pagFromId, from);
        s(this.cfg.pagToId, Math.min(end, total));
        s(this.cfg.pagTotalId, total);

        this._renderPagination(pages);
    }

    _renderPagination(totalPages) {
        const container = this.$(this.cfg.pagBtnsId);
        if (!container) return;
        container.innerHTML = "";

        const base =
            "px-2.5 py-1 rounded-md text-[11.5px] border border-black/[.07] dark:border-white/[.06]";
        const cls = {
            off:
                base +
                " bg-white dark:bg-[#141c2e] text-gray-300 dark:text-gray-600 cursor-not-allowed",
            on:
                base +
                " bg-white dark:bg-[#141c2e] text-gray-600 dark:text-[#909ab0] hover:bg-gray-50 cursor-pointer",
            act:
                base +
                " bg-pslb-cyan-500 text-white font-medium cursor-pointer border-pslb-cyan-500",
        };

        const btn = (label, disabled, active, cb) => {
            const b = document.createElement("button");
            b.textContent = label;
            b.disabled = disabled;
            b.className = active ? cls.act : disabled ? cls.off : cls.on;
            if (!disabled) b.addEventListener("click", cb);
            return b;
        };

        container.appendChild(
            btn("← Préc.", this.currentPage === 1, false, () => {
                this.currentPage--;
                this._render();
            }),
        );

        let s = Math.max(1, this.currentPage - 2);
        let e = Math.min(totalPages, s + 4);
        if (e - s < 4) s = Math.max(1, e - 4);

        for (let i = s; i <= e; i++) {
            const pg = i;
            container.appendChild(
                btn(i, false, i === this.currentPage, () => {
                    this.currentPage = pg;
                    this._render();
                }),
            );
        }

        container.appendChild(
            btn("Suiv. →", this.currentPage >= totalPages, false, () => {
                this.currentPage++;
                this._render();
            }),
        );
    }
};
