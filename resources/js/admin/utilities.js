/// ── Dark mode ──────────────────────────────────────────────
function toggleTheme() {
    const isDark = document.documentElement.classList.toggle('dark');
    localStorage.setItem('cdpat-theme', isDark ? 'dark' : 'light');
    updateIcons(isDark);
}

function updateIcons(isDark) {
    document.getElementById('ic-sun')?.classList.toggle('hidden', !isDark);
    document.getElementById('ic-moon')?.classList.toggle('hidden', isDark);
}

// Attendre le DOM pour les icônes
document.addEventListener('DOMContentLoaded', function() {
    updateIcons(document.documentElement.classList.contains('dark'));
});

// ── Sidebar dropdowns ──────────────────────────────────────
function toggleDD(id) {
    document.getElementById(id).classList.toggle("open");
}

// ── Mobile sidebar ────────────────────────────────────────
function openSidebar() {
    document.getElementById("sidebar")?.classList.add("mobile-open");
    document.getElementById("sidebar-overlay")?.classList.add("show");
    document.body.style.overflow = "hidden";
}

function closeSidebar() {
    document.getElementById("sidebar")?.classList.remove("mobile-open");
    document.getElementById("sidebar-overlay")?.classList.remove("show");
    document.body.style.overflow = "";
}

// ── Profile dropdown ──────────────────────────────────────
function toggleProfile(e) {
    e.stopPropagation();
    document.getElementById("profile-dropdown").classList.toggle("show");
}
document.addEventListener("click", function (e) {
    const wrap = document.getElementById("profile-wrap");
    if (!wrap.contains(e.target)) {
        document.getElementById("profile-dropdown").classList.remove("show");
    }
});
