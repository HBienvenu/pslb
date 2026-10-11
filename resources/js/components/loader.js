(function () {
    // ── Crée le SVG spinner ───────────────────────────────────────────────
    function makeSpinner() {
        const svg = document.createElementNS(
            "http://www.w3.org/2000/svg",
            "svg",
        );
        svg.setAttribute("viewBox", "0 0 24 24");
        svg.setAttribute("fill", "none");
        svg.classList.add(
            "w-4",
            "h-4",
            "animate-spin",
            "inline-block",
            "ml-1.5",
            "shrink-0",
            "_pslb-spinner", // classe sentinelle pour le reset
        );
        svg.innerHTML = `
            <circle class="opacity-25" cx="12" cy="12" r="10"
                stroke="currentColor" stroke-width="3"/>
            <path class="opacity-75" fill="currentColor"
                d="M4 12a8 8 0 018-8v8H4z"/>
        `;
        return svg;
    }

    // ── Active le loader sur un bouton ───────────────────────────────────
    function activateLoader(btn) {
        // Évite le double déclenchement
        if (btn._pslbLoading) return;
        btn._pslbLoading = true;

        // Injecte le spinner après le contenu existant
        btn.appendChild(makeSpinner());

        btn.disabled = true;
        btn.classList.add("opacity-75", "cursor-not-allowed");
    }

    // ── Réinitialise le loader sur un bouton ─────────────────────────────
    function resetLoader(btn) {
        if (!btn) return;

        // Retire le(s) spinner(s) éventuellement injecté(s)
        btn.querySelectorAll("._pslb-spinner").forEach((s) => s.remove());

        btn.disabled = false;
        btn.classList.remove("opacity-75", "cursor-not-allowed");
        btn._pslbLoading = false;
    }

    // ── Attache le listener submit à un formulaire ───────────────────────
    function attachLoader(form) {
        if (form._loaderAttached) return;
        form._loaderAttached = true;

        form.addEventListener("submit", function () {
            const btn = form.querySelector('[type="submit"]');
            if (btn) activateLoader(btn);
        });
    }

    // ── Initialise tous les formulaires visibles ──────────────────────────
    function init() {
        document.querySelectorAll("form").forEach(attachLoader);
    }

    // ── Reset après chargement Turbo (erreurs de validation, retour page) ─
    function resetAll() {
        document.querySelectorAll("form").forEach((form) => {
            const btn = form.querySelector('[type="submit"]');
            resetLoader(btn);
            form._loaderAttached = false;
        });
        init();
    }

    // ── Événements ───────────────────────────────────────────────────────
    document.addEventListener("turbo:load", init);

    document.addEventListener("turbo:frame-load", function (e) {
        e.target.querySelectorAll("form").forEach((form) => {
            const btn = form.querySelector('[type="submit"]');
            resetLoader(btn);
            form._loaderAttached = false;
            attachLoader(form);
        });
    });

    document.addEventListener("turbo:render", resetAll);

    // Premier chargement sans Turbo
    if (document.readyState === "loading") {
        document.addEventListener("DOMContentLoaded", init);
    } else {
        init();
    }
})();
