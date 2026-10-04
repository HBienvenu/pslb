// Les script a l'interieur des pages pourront fonctionner
let turboFirstLoad = true;
document.addEventListener("turbo:load", function () {
    if (turboFirstLoad) {
        turboFirstLoad = false;
        return; // premier chargement : ne pas dispatcher DOMContentLoaded
    }
    document.dispatchEvent(new Event("DOMContentLoaded"));
});
