import './app.js';
import AOS from "aos";
import "aos/dist/aos.css";
import SmoothScroll from "smooth-scroll";
import "./site_components/script_module.js";

// Initialisation AOS
function initAOS() {
    document.querySelectorAll("[data-aos]").forEach((el) => {
        el.classList.remove("aos-animate", "aos-init");
    });
    AOS.init({ duration: 150, once: true });
    requestAnimationFrame(() => requestAnimationFrame(() => AOS.refreshHard()));
}

// Pour le scroll
function initSmoothScroll() {
    new SmoothScroll('a[href^="#"]:not([href="#"])', {
        speed: 500,
        speedAsDuration: true,
        offset: 80,
    });
}

function initAll() {
    initAOS();
    initSmoothScroll();
}

// Navigations Turbo suivantes
document.addEventListener("turbo:load", initAll);

// Fallback : turbo:load déjà passé avant que ce module s'exécute
if (document.readyState !== "loading") {
    requestAnimationFrame(() => requestAnimationFrame(initAll));
}

document.addEventListener("turbo:render", () => {
    requestAnimationFrame(() => requestAnimationFrame(() => AOS.refresh()));
});
