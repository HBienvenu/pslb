import './app.js';
import "./admin_components/modal.js";
import "./admin_components/script_table.js";

// ══ Persistance du scroll de la sidebar ══
const SIDEBAR_SCROLL_KEY = "pslb-sidebar-scroll";

document.addEventListener("turbo:before-visit", () => {
    const sidebar = document.querySelector("#sidebar nav");
    if (sidebar) {
        sessionStorage.setItem(SIDEBAR_SCROLL_KEY, sidebar.scrollTop);
    }
});

// turbo:render : DOM prêt mais avant le paint → pas de flash
document.addEventListener("turbo:render", () => {
    const sidebar = document.querySelector("#sidebar nav");
    const saved = sessionStorage.getItem(SIDEBAR_SCROLL_KEY);
    if (sidebar && saved !== null) {
        sidebar.scrollTop = parseInt(saved, 10);
    }
});
