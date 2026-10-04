import tinymce from "tinymce";

import 'tinymce/icons/default';
import 'tinymce/themes/silver';
import 'tinymce/models/dom';

import 'tinymce/plugins/table';
import 'tinymce/plugins/lists';
import 'tinymce/plugins/code';

// Ajouts légers et utiles
import 'tinymce/plugins/advlist';       // meilleures options de listes (puces carrées, romain, etc.)
import 'tinymce/plugins/autolink';      // convertit automatiquement les URLs tapées en liens
import 'tinymce/plugins/wordcount';     // compteur de mots/caractères en bas de l'éditeur
import 'tinymce/plugins/fullscreen';    // bouton plein écran, pratique pour les articles longs

// Import direct des CSS du skin (Vite les bundle correctement)
import 'tinymce/skins/ui/oxide/skin.css';
import 'tinymce/skins/ui/oxide/content.css';
import contentCss from 'tinymce/skins/content/default/content.css?url';

function initTinyMCE() {
    tinymce.remove();

    // Ne tente l'initialisation que si un champ existe réellement sur la page —
    // évite tout appel inutile à tinymce.init() (et ses effets de bord) sur des
    // pages qui n'ont pas d'éditeur, si ce module venait à être chargé ailleurs.
    if (!document.querySelector(".tinymce")) return;

    tinymce.init({
        selector: ".tinymce",
        content_css: contentCss,
        license_key: 'gpl',
        height: 400,
        plugins: "lists link table code advlist autolink wordcount fullscreen",
        toolbar: "undo redo | bold italic | bullist numlist | link image table | searchreplace | fullscreen | code",
        skin: false,
    });
}

document.addEventListener("turbo:load", initTinyMCE);

// Détruit les instances AVANT que Turbo ne mette la page en cache (bfcache-like).
// Sans ça, l'iframe de TinyMCE est capturé dans un état incohérent par le
// snapshot Turbo, et la ré-initialisation suivante échoue avec
// "document is not in standards mode" — le mode de rendu de l'iframe restauré
// n'est plus détecté correctement par le navigateur.
document.addEventListener("turbo:before-cache", () => {
    tinymce.remove();
});
