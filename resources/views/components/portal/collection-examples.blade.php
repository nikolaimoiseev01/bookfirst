<section
    class="max-w-(--breakpoint-3xl) mx-auto w-[90%] flex justify-between items-center relative mb-32 lg:flex-col gap-16"
    x-data="exSlider()"
    x-init="init()"
>
    <svg class="absolute left-[15%] -bottom-28 w-16 lg:hidden" id="Слой_1" data-name="Слой 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 254.8 358.1">
        <path
            d="M195.6,122.6c23.1,21.7,31,54.1,12.8,98.5S98.4,283.6,69.1,256c-29.5-27.5-15.3-130.7,33-134.6C150.6,117.4,172.3,100.8,195.6,122.6Z"
            style="fill:#ffedcc"/>
        <g style="opacity:0.4000000059604645">
            <polygon points="103.2 0 0 235.9 151.7 358.1 254.8 122.2 103.2 0" style="fill:none"/>
        </g>
        <g style="opacity:0.5">
            <polygon points="163.5 284 27.1 178.7 87.3 87.4 223.7 192.6 163.5 284" style="fill:none"/>
        </g>
    </svg>
    <div class="flex flex-col md:w-full">
        <!-- Изображение -->
        <div class="relative overflow-hidden">
            <template x-for="(img, index) in images" :key="index">
                <img
                    :src="img"
                    alt=""
                    class="w-full object-cover transition-opacity duration-500"
                    :class="currentIndex === index ? 'opacity-100' : 'opacity-0 absolute inset-0'"
                >
            </template>
        </div>

        <!-- Переключатели -->
        <div class="flex items-center mx-auto mt-4">
            <x-heroicon-o-chevron-left
                class="w-12 transition duration-100 hover:scale-110 cursor-pointer"
                @click="prev"
            />
            <div class="h-[2px] w-56 bg-dark-400 relative">
                <div
                    class="h-1 bg-brown-300 absolute -top-px transition-all duration-500"
                    :style="`width: ${100 / images.length}%; left: ${(100 / images.length) * currentIndex}%;`"
                ></div>
            </div>
            <x-heroicon-o-chevron-right
                class="w-12 transition duration-100 hover:scale-110 cursor-pointer"
                @click="next"
            />
        </div>
    </div>
    <!-- Тексты -->
    <div class="max-w-3xl flex flex-col xl:max-w-xl lg:justify-center lg:text-center">
        <h2 class="mb-4 md:text-4xl" x-ref="titleEl"></h2>
        <p x-ref="descEl" class="md:xl"></p>
        <div class="flex gap-4 mt-8 md:flex-wrap lg:justify-center" lg:mx-auto>
            <x-ui.link href="{{route('portal.collections.released')}}">Подробнее</x-ui.link>
            <x-ui.link :navigate="false" target="_blank" href="https://www.ozon.ru/product/broshyura-1869093918">Купить на Ozon</x-ui.link>
            <x-ui.link :navigate="false" target="_blank" href="https://www.amazon.com/%D0%A1%D0%BE%D0%B2%D1%80%D0%B5%D0%BC%D0%B5%D0%BD%D0%BD%D1%8B%D0%B9-%D0%94%D1%83%D1%85-%D0%9F%D0%BE%D1%8D%D0%B7%D0%B8%D0%B8-19-2-Russian/dp/035993868X/ref=sr_1_3?dib=eyJ2IjoiMSJ9._ALVMZa-Ri7dggCl0Nk15REeLqZ3CLJ3Vc8_9SPooTU17a1f5fIEDJhTMFWrmX9S5JjXfzGRcPy9rMu70hEng_pFGXmt-65iecRNXwLwgWpZjeI-qazoFZYsPxRa2zzuqw2shXJ4gLgXEuCd0Ffs7VKG4UO8k-QtUcnUe2WmPzXmy6hNLQNcBYSVAecxesnRl3kFuFd5s4wJRwW81Q2-3WuoqO6KcuZzDb3R9_egJ9c.N3raipLn_zNwY_vPaSv7jXfIVFhZpDF4CL3fIJy1v8o&dib_tag=se&qid=1763407494&refinements=p_27%3A%26%231053%3B%26%231048%3B+%26%231055%3B%26%231077%3B%26%231088%3B%26%231074%3B%26%231072%3B%26%231103%3B+%26%231050%3B%26%231085%3B%26%231080%3B%26%231075%3B%26%231072%3B&s=books&sr=1-3&text=%26%231053%3B%26%231048%3B+%26%231055%3B%26%231077%3B%26%231088%3B%26%231074%3B%26%231072%3B%26%231103%3B+%26%231050%3B%26%231085%3B%26%231080%3B%26%231075%3B%26%231072%3B">Купить на Amazon</x-ui.link>
        </div>
    </div>
</section>


@push('scripts')
    <script>
        function exSlider() {
            return {
                currentIndex: 0,
                isAnimating: false, // ← флаг анимации
                images: [
                    '/fixed/main_ex_0.png',
                    '/fixed/main_ex_1.png',
                    '/fixed/main_ex_2.png'
                ],
                loadAnime() {
                    return new Promise((resolve) => {
                        if (window.anime) {
                            resolve();
                            return;
                        }

                        const script = document.createElement("script");
                        script.src = "/plugins/anime.min.js";
                        script.onload = () => {
                            window.anime = anime;
                            resolve();
                        };
                        document.head.appendChild(script);
                    });
                },
                titles: [
                    'Современный Дух Поэзии',
                    'Сокровенные Мысли',
                    'Тематические сборники'
                ],
                descs: [
                    'Более 60-ти изданных выпусков сборников стихотворений современных поэтов. Взгляды поэтов неоднозначны, многослойны и объёмны, они олицетворяют настоящую жизнь.',
                    'Мысли – это неотъемлемая часть нашей жизни. В сборнике «Сокровенные мысли» мы собираем авторов, проза которых заставит глубоко окунуться в мир непостижимого, которым мы окружены.',
                    'Так же мы часто выпускаем тематические сборники. Новогодние сборники, военные рассказы, фанфики сегодняшних писателей как нельзя лучше передают культуру современной литературы.'
                ],

                async init() {
                    await this.loadAnime();
                    this.animateText(this.$refs.titleEl, this.titles[this.currentIndex], 60, 1000);
                    this.animateText(this.$refs.descEl, this.descs[this.currentIndex], 18, 300);
                },

                next() {
                    this.changeSlide(1);
                },

                prev() {
                    this.changeSlide(-1);
                },

                changeSlide(direction) {
                    if (this.isAnimating) return; // блокируем повторное нажатие
                    this.isAnimating = true;

                    const titleEl = this.$refs.titleEl;
                    const descEl = this.$refs.descEl;

                    const allLetters = [
                        ...titleEl.querySelectorAll('.letter'),
                        ...descEl.querySelectorAll('.letter')
                    ];

                    anime({
                        targets: allLetters,
                        opacity: [1, 0],
                        duration: 20,
                        easing: 'easeOutQuad',
                        complete: () => {
                            this.currentIndex = (this.currentIndex + direction + this.images.length) % this.images.length;

                            this.$nextTick(() => {
                                this.animateText(titleEl, this.titles[this.currentIndex], 60, 100);
                                this.animateText(descEl, this.descs[this.currentIndex], 18, 30, () => {
                                    this.isAnimating = false; // ← разблокируем после анимации
                                });
                            });
                        }
                    });
                },

                animateText(el, text, delayStep = 60, duration = 100, onComplete = null) {
                    el.innerHTML = text
                        .replace(/\S+\s|\S+$/g, "<span class='word inline-block m-[.10em]'>$&</span>");

                    el.querySelectorAll('.word').forEach(wordEl => {
                        wordEl.innerHTML = wordEl.textContent
                            .replace(/\S/g, "<span class='letter' style='display: inline-block;'>$&</span>");
                    });

                    anime.timeline({loop: false})
                        .add({
                            targets: el.querySelectorAll('.letter'),
                            opacity: [0, 1],
                            scale: [0, 1],
                            duration: duration,
                            elasticity: 600,
                            delay: (el, i) => delayStep * i,
                            complete: onComplete
                        });
                }
            }
        }
    </script>
@endpush

