import "@hotwired/turbo";
import "./components/turbo_hotwire_config.js";
import Swal from "sweetalert2";
import "./components/tooltip.js";
import "./components/loader.js";
import "./components/tom_select.js";
import "./components/datepicker.js";
import "./components/tinymce.js";

// rend Swal accessible dans les scripts inline
window.Swal = Swal;
