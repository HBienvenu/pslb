// ── Tooltip global ────────────────────────────────────────────────────
(function () {
    let hideTimer = null;

    // Récupère ou crée le div tooltip dans le body
    function getEl() {
        let el = document.getElementById("pslb-tooltip");
        if (!el) {
            el = document.createElement("div");
            el.id = "pslb-tooltip";
            document.body.appendChild(el);
        }
        return el;
    }

    function show(target) {
        const tip = target.getAttribute("data-tip");
        if (!tip) return;

        const el = getEl();
        clearTimeout(hideTimer);
        el.textContent = tip;

        const isDark = document.documentElement.classList.contains("dark");
        el.style.background = isDark ? "#374151" : "#1f2937";

        el.style.visibility = "hidden";
        el.classList.remove("show");
        el.style.display = "block";

        const rect = target.getBoundingClientRect();
        const tipW = el.offsetWidth;
        const tipH = el.offsetHeight;

        let left = rect.left + rect.width / 2 - tipW / 2;
        let top = rect.top - tipH - 10;

        left = Math.max(8, Math.min(left, window.innerWidth - tipW - 8));

        if (top < 8) {
            top = rect.bottom + 10;
        }

        el.style.left = left + "px";
        el.style.top = top + "px";
        el.style.visibility = "";

        requestAnimationFrame(() => el.classList.add("show"));
    }

    function hide() {
        hideTimer = setTimeout(() => {
            const el = document.getElementById("pslb-tooltip");
            if (el) el.classList.remove("show");
        }, 80);
    }

    // ── Listeners sur document (une seule fois, survivent à Turbo) ──
    // Guard pour ne pas attacher plusieurs fois si le script est rechargé
    if (!window._pslbTooltipInitialized) {
        window._pslbTooltipInitialized = true;

        document.addEventListener("mouseover", function (e) {
            const target = e.target.closest("[data-tip]");
            if (target) show(target);
        });

        document.addEventListener("mouseout", function (e) {
            const target = e.target.closest("[data-tip]");
            if (target) hide();
        });

        document.addEventListener("scroll", hide, true);
        window.addEventListener("resize", hide);
    }

    // ── Recrée le div après chaque navigation Turbo ──
    document.addEventListener("turbo:load", function () {
        getEl(); // s'assure que le div est dans le body actuel
        hide(); // cache tout tooltip résiduel de la page précédente
    });
})();
