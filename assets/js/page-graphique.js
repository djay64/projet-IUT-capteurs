/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import '../css/bootstrap.min.css';
import '../css/main.css';
import '../css/page-graphique.css';

// Need jQuery? Install it with "yarn add jquery", then uncomment to import it.
 import $ from 'jquery';

console.log('Hello Webpack Encore! Edit me in assets/js/app.js');

 

document.getElementById("nav-item-graphique").classList.add("active");

function exporter() {
    var graphique = document.getElementById('myChart');
    var urlImage = graphique.toDataURL();

    window.open(urlImage,'_blank', null);
}
