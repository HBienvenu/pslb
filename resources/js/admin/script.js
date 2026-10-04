// Evite le flash du mode quand on est en dark mode et on actualise
(function () {
    const theme = localStorage.getItem("cdpat-theme");
    if (
        theme === "dark" ||
        (!theme && window.matchMedia("(prefers-color-scheme: dark)").matches)
    ) {
        document.documentElement.classList.add("dark");
    }
})();
