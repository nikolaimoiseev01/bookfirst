import './bootstrap';
import {livewire_hot_reload} from 'virtual:livewire-hot-reload'
import $ from 'jquery'
import Swiper from 'swiper';
import 'swiper/css';
import 'swiper/css/navigation';
import 'swiper/css/pagination';
import {Navigation, Pagination} from "swiper/modules";
import collapse from '@alpinejs/collapse'
import "delicious-hamburgers/scss/_base.scss"
import "delicious-hamburgers/scss/animations/_converge.scss";
import Snowflakes from "magic-snowflakes"


Swiper.use([Navigation, Pagination]);

Alpine.plugin(collapse)

window.$ = $;
window.Swiper = Swiper;

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

window.addEventListener('swal', event => {
    showSwal(event.detail.type, event.detail.title, event.detail.text, event.detail.confirmButtonText, event.detail.livewireMethod)
});


// window.showToast = function showToast(type, text) {
//     if (type === 'success') {
//         notyf.success(text);
//     }
// }


// window.addEventListener('toast', event => {
//     showToast(event.detail.type, event.detail.text)
// });




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
                    title: 'Внимание!',
                    html: `
                        <p>Чтобы выполнить это действие, пожалуйста, войдите в аккаунт или зарегистрируйтесь.</p>
                        <div class="flex justify-center gap-3 mt-4">
                            <a href="/login" wire:navigate class="!outline-none block border text-xl min-w-max flex gap-2 items-center justify-center rounded-lg py-1 px-8 cursor-pointer transition text-green-500 border-green-500 hover:bg-green-500 hover:text-white">Войти</a>
                            <a href="/register" wire:navigate class="!outline-noneblock border text-xl min-w-max flex gap-2 items-center justify-center rounded-lg py-1 px-8 cursor-pointer transition text-green-500 border-green-500 hover:bg-green-500 hover:text-white">Зарегистрироваться</a>
                        </div>
                    `,
                    showConfirmButton: false
                });
            });
        });
    }
}



const params = new URLSearchParams(window.location.search);
const confirmPayment = params.get('confirm_payment');

if (confirmPayment) {
    let title = '';
    let html = '';

    switch (confirmPayment) {
        case 'collection_participation':
            title = 'Оплата успешно завершена 🎉';
            html = '<p>Следующий шаг - дождаться этапа предварительной проверки.</p>';
            break;

        case 'own_book_without_print':
            title = 'Оплата прошла успешно 💫';
            html = '<p>Ваша книга принята в работу. Следующий шаг - дождаться этапа предварительной проверки.</p>';
            break;

        case 'own_book_print_only':
            title = 'Оплата прошла успешно 💫';
            html = '<p>Мы начали подготовку к печати. Как только мы отправим заказ в работу, вы получите отдельное уведомление, а общий статус книги изменится. Обычно это занимает 3 дня.</p>';
            break;

        case 'ext_promotion':
            title = 'Оплата прошла успешно 💫';
            html = '<p>В течение 3-х дней мы начнем продвижение. Вы получите отдельное уведомление по Email, а за процессом можно будет следить на этой странице.</p>';
            break;

        case 'collection_purchase':
            title = 'Оплата прошла успешно 💫';
            html = '<p>На этой странице вы можете скачать электронную версию приобретенных книг.</p>';
            break;
    }

    Swal.fire({
        title,
        html,
        icon: 'success',
        showConfirmButton: false
    });
}


//region -- Новогодние снежинки
function makeSnowFlakes() {
    var count_snows = 20
    if(window.innerWidth > 768) {
        count_snows = 20
    } else {
        count_snows = 10
    }
    new Snowflakes({
        color: '#5ECDEF', // Default: "#5ECDEF"
        container: document.body, // Default: document.body
        count: count_snows, // 100 snowflakes. Default: 50
        minOpacity: 0.4, // From 0 to 1. Default: 0.6
        maxOpacity: 0.8, // From 0 to 1. Default: 1
        minSize: 10, // Default: 10
        maxSize: 20, // Default: 25
        rotation: true, // Default: true
        speed: 1, // The property affects the speed of falling. Default: 1
        wind: true, // Without wind. Default: true
        zIndex: 9997 // Default: 9999
    });
}
//endregion

document.addEventListener('DOMContentLoaded', () => {
    window.loggedCheck()
});

document.addEventListener('livewire:navigated', () => {
    window.loggedCheck()
    // makeSnowFlakes()
});

window.Cookie = {
    /**
     * Установить cookie
     */
    set: function (name, value, days = 30, path = '/') {
        let expires = '';
        if (days) {
            const date = new Date();
            date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
            expires = '; expires=' + date.toUTCString();
        }
        document.cookie = name + '=' + encodeURIComponent(value) + expires + '; path=' + path;
    },

    /**
     * Получить cookie
     */
    get: function (name) {
        const nameEQ = name + '=';
        const ca = document.cookie.split(';');
        for (let i = 0; i < ca.length; i++) {
            let c = ca[i].trim();
            if (c.indexOf(nameEQ) === 0) {
                return decodeURIComponent(c.substring(nameEQ.length));
            }
        }
        return null;
    },

    /**
     * Удалить cookie
     */
    delete: function (name, path = '/') {
        document.cookie = name + '=; Max-Age=-1; path=' + path;
    }
};


$(document).ready(function () {

    function getQueryParam(name) {
        const params = new URLSearchParams(window.location.search);
        return params.get(name);
    }

    const utmSource = getQueryParam('utm_source');
    const utmMedium = getQueryParam('utm_medium');
    const utmCampaign = getQueryParam('utm_campaign');
    const utmContent = getQueryParam('utm_content');

    // сохраняем только если они есть в URL
    if (utmSource) {
        Cookie.set('utm_source', utmSource, 30);
    }

    if (utmMedium) {
        Cookie.set('utm_medium', utmMedium, 30);
    }

    if (utmCampaign) {
        console.log(5)
        Cookie.set('utm_campaign', utmCampaign, 30);
    }

    if (utmContent) {
        Cookie.set('utm_content', utmContent, 30);
    }

});
