import TomSelect from 'tom-select';
import 'tom-select/dist/css/tom-select.bootstrap5.css';

// ── Helper Tom Select ──
window.initFormationSelect = function (id) {
    const element = document.getElementById(id);
    if (!element) return null;

    if (element.tomselect) element.tomselect.destroy();

    return new TomSelect(`#${id}`, {
        placeholder: "Rechercher…",
        allowEmptyOption: true,
        maxOptions: null,
        searchField: ["text"],
        render: {
            no_results: () =>
                `<div class="no-results">Aucune formation trouvée</div>`,
        },
    });
};

// ── Init générique sur un container (document ou turbo-frame) ──
function initAllTomSelects(root = document) {
    root.querySelectorAll("select[data-ts]").forEach((el) => {
        if (el.tomselect) el.tomselect.destroy();

        new TomSelect(el, {
            placeholder: el.dataset.placeholder ?? "Rechercher…",
            allowEmptyOption: true,
            maxOptions: null,
            searchField: ["text"],
            plugins: el.multiple ? ["remove_button"] : [],
            render: {
                no_results: () =>
                    `<div class="no-results">Aucun résultat</div>`,
            },
        });
    });
}

// Après navigation Turbo (changement de page)
document.addEventListener("turbo:load", () => initAllTomSelects());

// Après rechargement d'une turbo-frame (soumission formulaire)
document.addEventListener("turbo:frame-load", (e) =>
    initAllTomSelects(e.target),
);

// Sans Turbo
document.addEventListener("DOMContentLoaded", () => initAllTomSelects());
