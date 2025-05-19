import './bootstrap.js';
/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */
// any CSS you import will output into a single css file (app.css in this case)
// css
import './styles/app.css';
import $ from 'jquery';
import Swal from 'sweetalert2';
window.Swal = Swal;
window.$ = $;
import PerfectScrollbar from 'perfect-scrollbar';

// js files
import 'select2/dist/css/select2.min.css';
import 'select2';
import 'select2-bootstrap-5-theme/dist/select2-bootstrap-5-theme.min.css';
import './libs/perfect-scrollbar/perfect-scrollbar'
import 'bootstrap';
import './js/menu';
import './js/main';

$('.select2-dropdown-multiple').select2({
    theme: "bootstrap-5",
    multiple: true,
    closeOnSelect: false,
    width: '100%'

});
$('.select2-dropdown-single').select2({
    theme: "bootstrap-5",
    width: '100%'
});

var $container = $('.scrollable-container');
if ($container.length) {
    $container.each(function () {
        new PerfectScrollbar(this);
        $(this).removeClass('overflow-hidden'); // enable scrolling
    });
}