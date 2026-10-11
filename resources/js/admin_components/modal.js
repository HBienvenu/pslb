// ════════════════════════════════════════════════════════════
//  MODAL — fonctions globales (layout)
// ════════════════════════════════════════════════════════════

window.openModal = (id) => {
    document.getElementById(id)?.classList.remove("hidden");
    document.body.style.overflow = "hidden";
};

window.closeModal = (id) => {
    const modal = document.getElementById(id);
    if (!modal) return;

    modal.classList.add("hidden");
    document.body.style.overflow = "";

    _resetModalForm(modal);
};

window.handleModalBackdrop = (event, id) => {
    closeModal(id);
};

// ── Vue modal : listener délégué sur .view-btn ───────────────
document.addEventListener("click", function (e) {
    const btn = e.target.closest(".view-btn");
    if (!btn) return;

    const modalId = btn.dataset.viewModal;
    const modal = document.getElementById(modalId);
    if (!modal) return;

    _setViewSubtitle(modal, btn.dataset.viewSubtitle);
    _fillViewFields(modal, btn.dataset);

    openModal(modalId);
});

// ════════════════════════════════════════════════════════════
//  Fonctions utilitaires privées
// ════════════════════════════════════════════════════════════

function _setViewSubtitle(modal, subtitle) {
    if (!subtitle) return;
    const el = modal.querySelector("[data-view-subtitle-target]");
    if (el) el.textContent = subtitle;
}

function _fillViewFields(modal, dataset) {
    Object.entries(dataset).forEach(([key, value]) => {
        if (!key.startsWith("field")) return;
        const fieldName = _camelToSnake(key.replace("field", ""));
        const el = modal.querySelector(`#view-${fieldName}`);
        if (el) _renderViewField(el, fieldName, value);
    });
}

function _renderViewField(el, fieldName, value) {
    if (fieldName === "users") {
        el.innerHTML = `
            <svg class="w-[10px] h-[10px] mr-1" fill="none" stroke="currentColor"
                 stroke-width="2.5" viewBox="0 0 24 24">
                <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2M9 11a4 4 0 100-8
                         4 4 0 000 8zM23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/>
            </svg>
            ${value} utilisateur${parseInt(value) > 1 ? "s" : ""}`;
    } else if (fieldName === "statut") {
        const isActif = value === "Actif";
        el.innerHTML = `
            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[11px] font-medium
                ${
                    isActif
                        ? "bg-emerald-500/10 text-emerald-700 dark:text-emerald-400"
                        : "bg-gray-200 dark:bg-gray-700 text-gray-500 dark:text-gray-400"
                }">
                <span class="w-1.5 h-1.5 rounded-full inline-block ${isActif ? "bg-emerald-500" : "bg-gray-400"}"></span>
                ${value}
            </span>`;
    } else if (fieldName === "cv") {
        const block = document.getElementById("view-cv-block");
        const empty = document.getElementById("view-cv-empty");

        if (block) block.classList.toggle("hidden", !value);
        if (empty) empty.classList.toggle("hidden", !!value);

        if (value) {
            el.innerHTML = `
            <a href="/storage/cv/${value}" target="_blank"
               class="hover:text-pslb-cyan-500 transition-colors underline underline-offset-2">
                ${value}
            </a>`;
        } else {
            el.textContent = "—";
        }
    } else {
        el.textContent = value || "—";
    }
}

function _resetModalForm(modal) {
    modal
        .querySelectorAll('input:not([type="hidden"]), textarea, select')
        .forEach((el) => {
            if (el.type === "checkbox" || el.type === "radio") {
                el.checked = el.defaultChecked;
            } else if (el.tagName === "SELECT") {
                el.selectedIndex = 0;
            } else {
                el.value = "";
            }

            el.classList.remove(
                "border-red-400",
                "dark:border-red-500",
                "focus:border-red-500",
            );

            el.classList.add(
                "border-black/[.07]",
                "dark:border-white/[.06]",
                "focus:border-pslb-cyan-500",
            );
        });

    modal
        .querySelectorAll(
            "p.text-red-500, p.text-red-400, div.bg-red-50, div.bg-red-900\\/20",
        )
        .forEach((el) => el.remove());
}

function _camelToSnake(str) {
    return str
        .replace(/[A-Z]/g, (l) => "_" + l.toLowerCase())
        .replace(/^_/, "");
}

