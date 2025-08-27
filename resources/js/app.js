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
