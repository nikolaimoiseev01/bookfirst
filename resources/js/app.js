import './bootstrap';
import Typewriter from 'typewriter-effect/dist/core';
import {livewire_hot_reload} from 'virtual:livewire-hot-reload'
import $ from 'jquery'
import Swiper from 'swiper';
import 'swiper/css';
import 'swiper/css/navigation';
import 'swiper/css/pagination';
import {Navigation, Pagination} from "swiper/modules";

Swiper.use([Navigation, Pagination]);
import collapse from '@alpinejs/collapse'
import "delicious-hamburgers"

Alpine.plugin(collapse)

window.$ = $;
window.Typewriter = Typewriter;

window.Swiper = Swiper;

livewire_hot_reload();

function showSwal(icon, title, text) {
    Swal.fire({
        icon: icon,
        title: title,
        html: '<p>' + text + '</p>',
        showConfirmButton: false,
    });
}

window.shoSwal = showSwal

window.addEventListener('swal', event => {
    showSwal(event.detail.icon, event.detail.title, event.detail.text)
});


window.disableSendButtons = function (state) {
    const submitButtons = document.querySelectorAll('.submitButton');
    if (state) {
        submitButtons.forEach(function (el) {
            el.classList.add('loading');
            el.setAttribute('disabled', 'true');
        });
    } else {
        submitButtons.forEach(function (el) {
            el.classList.remove('loading');
            el.removeAttribute('disabled');
        });
    }
};

