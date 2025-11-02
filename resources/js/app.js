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
import {Notyf} from "notyf";
import 'notyf/notyf.min.css';

Alpine.plugin(collapse)

window.$ = $;
window.Typewriter = Typewriter;

window.Swiper = Swiper;
window.notyf = new Notyf();

livewire_hot_reload();



window.showSwal = function showSwal(type, title, text, confirmButtonText = '', livewireMethod = []) {
    let showConfirmFlg = confirmButtonText.length > 0;
    Swal.fire({
        icon: type,
        title: title,
        html: '<p>' + text + '</p>',
        showConfirmButton: showConfirmFlg,
        confirmButtonText: confirmButtonText,
        showCancelButton: showConfirmFlg,
        cancelButtonText: 'Отмена',
    }).then((result) => {
        if (result.isConfirmed) {
             Livewire.dispatch(livewireMethod[0], [livewireMethod[1]]);
        }
    });
}

window.showToast = function showToast(type, text) {
    if (type === 'success') {
        notyf.success(text);
    }
}


window.addEventListener('swal', event => {
    showSwal(event.detail.type, event.detail.title, event.detail.text, event.detail.confirmButtonText, event.detail.livewireMethod)
});

window.addEventListener('toast', event => {
    showToast(event.detail.type, event.detail.text)
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

window.loggedCheck = function () {
    const isLogged = document.querySelector('meta[name="user-logged-in"]').content === 'true';

    if (!isLogged) {
        // Находим все элементы, для которых нужно ограничить действие
        document.querySelectorAll('[data-check-logged]').forEach(el => {
            // Удаляем все wire:click и href
            [...el.attributes].forEach(attr => {
                if (attr.name.startsWith('wire:') || attr.name === 'href' || attr.name === '@click') {
                    el.removeAttribute(attr.name);
                }
            });

            // Вешаем swal вместо стандартного клика
            el.addEventListener('click', e => {
                e.preventDefault();
                Swal.fire({
                    title: 'Авторизация',
                    text: 'Чтобы выполнить это действие, пожалуйста, войдите в аккаунт',
                    icon: 'info',
                    confirmButtonText: 'Войти',
                    showCancelButton: true,
                    cancelButtonText: 'Отмена',
                }).then(result => {
                    if (result.isConfirmed) {
                        window.location.href = '/login'; // или другой путь для входа
                    }
                });
            });
        });
    }
}



document.addEventListener('DOMContentLoaded', () => {
    window.loggedCheck()
});

Livewire.hook('morph.updated', ({ el, component }) => {
    // window.loggedCheck()
})


const params = new URLSearchParams(window.location.search);
const confirmPayment = params.get('confirm_payment');

if (confirmPayment === 'collection_participation') {
    Swal.fire({
        title: 'Оплата успешно завершена 🎉',
        html: '<p>Следующий шаг - дождаться этапа предварительной проверки.</p>',
        icon: 'success',
        showConfirmButton: false
    });
}
