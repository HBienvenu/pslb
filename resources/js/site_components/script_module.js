import { animate } from "animejs";

function initMobileMenu() {
    const menuBtn = document.getElementById("mobile-menu-btn");
    const mobileMenu = document.getElementById("mobile-menu");

    // ── Reset des états à chaque appel ──
    let menuOpen = false;

    if (!menuBtn || !mobileMenu) return;

    // ── Reset visuel propre à chaque navigation ──
    mobileMenu.classList.add("hidden");
    mobileMenu.style.maxHeight = "0px";
    mobileMenu.style.overflow = "hidden";
    mobileMenu.style.opacity = "0";
    menuBtn.classList.remove("is-open");

    function getScrollHeight(el) {
        el.style.maxHeight = "none";
        const h = el.scrollHeight;
        el.style.maxHeight = "0px";
        return h;
    }

    // ── Accordéons mobiles (Espaces, A propos…) ──
    document.querySelectorAll(".mobile-accordion").forEach((acc) => {
        const btn = acc.querySelector(".mobile-acc-btn");
        const panel = acc.querySelector(".mobile-acc-panel");
        const chevron = acc.querySelector(".acc-chevron");
        if (!btn || !panel) return;

        // Reset état accordéon - TOUJOURS FERMÉ au départ, même si un sous-lien est actif
        panel.style.maxHeight = "0px";
        panel.style.opacity = "0";
        if (chevron) chevron.style.transform = "rotate(0deg)";

        // Clone pour supprimer les anciens listeners
        const newBtn = btn.cloneNode(true);
        btn.replaceWith(newBtn);

        // Récupérer les nouveaux éléments après remplacement
        const finalBtn = acc.querySelector(".mobile-acc-btn");
        const finalPanel = acc.querySelector(".mobile-acc-panel");
        const finalChevron = acc.querySelector(".acc-chevron");

        finalBtn.addEventListener("click", (e) => {
            e.stopPropagation();

            // Vérifier si le panneau est ouvert
            const isOpen =
                finalPanel.style.maxHeight !== "0px" &&
                finalPanel.style.maxHeight !== "";

            // Ferme tous les autres accordéons
            document.querySelectorAll(".mobile-accordion").forEach((other) => {
                if (other === acc) return;
                const op = other.querySelector(".mobile-acc-panel");
                const oc = other.querySelector(".acc-chevron");
                if (op) {
                    op.style.maxHeight = "0px";
                    op.style.opacity = "0";
                }
                if (oc) oc.style.transform = "rotate(0deg)";
            });

            if (isOpen) {
                // Fermer l'accordéon courant
                finalPanel.style.maxHeight = "0px";
                finalPanel.style.opacity = "0";
                if (finalChevron) finalChevron.style.transform = "rotate(0deg)";
            } else {
                // Ouvrir l'accordéon courant
                const scrollHeight = finalPanel.scrollHeight;
                finalPanel.style.maxHeight = scrollHeight + "px";
                finalPanel.style.opacity = "1";
                if (finalChevron)
                    finalChevron.style.transform = "rotate(180deg)";
            }
        });
    });

    // ── Menu burger principal ──

    // Clone pour supprimer les anciens listeners
    const newBtn = menuBtn.cloneNode(true);
    menuBtn.replaceWith(newBtn);

    newBtn.addEventListener("click", () => {
        // Relire après cloneNode
        const freshMenu = document.getElementById("mobile-menu");

        if (!menuOpen) {
            freshMenu.classList.remove("hidden");
            freshMenu.style.maxHeight = "0px";
            const h = getScrollHeight(freshMenu);
            animate(freshMenu, {
                maxHeight: [0, h],
                opacity: [0, 1],
                duration: 380,
                easing: "easeOutCubic",
                complete: () => {
                    freshMenu.style.maxHeight = "calc(100vh - 80px)";
                    freshMenu.style.overflowY = "auto";
                },
            });
            newBtn.classList.add("is-open");
            menuOpen = true;
        } else {
            freshMenu.style.overflowY = "hidden";
            const h = freshMenu.scrollHeight;
            freshMenu.style.maxHeight = h + "px";
            animate(freshMenu, {
                maxHeight: [h, 0],
                opacity: [1, 0],
                duration: 320,
                easing: "easeInOutCubic",
                complete: () => freshMenu.classList.add("hidden"),
            });
            newBtn.classList.remove("is-open");
            menuOpen = false;

            // Ferme tous les accordéons et réinitialise les flèches
            document.querySelectorAll(".mobile-acc-panel").forEach((p) => {
                p.style.maxHeight = "0px";
                p.style.opacity = "0";
            });
            document.querySelectorAll(".acc-chevron").forEach((c) => {
                c.style.transform = "rotate(0deg)";
            });
        }
    });
}

// ── Déclenché à chaque navigation Turbo ET au premier chargement ──
document.addEventListener("turbo:load", initMobileMenu);
// Pour le chargement initial classique
if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", initMobileMenu);
} else {
    initMobileMenu();
}
