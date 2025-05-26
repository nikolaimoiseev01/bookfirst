<section
    class="max-w-screen-3xl mx-auto w-[90%] flex justify-between items-center relative mb-32"
    x-data="exSlider()"
    x-init="init()"
>
    <svg class="absolute left-[15%] -bottom-28 w-16" id="Слой_1" data-name="Слой 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 254.8 358.1">
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
    <div class="flex flex-col">
        <!-- Изображение -->
        <div class="w-[650px] h-[340px] relative overflow-hidden">
            <template x-for="(img, index) in images" :key="index">
                <img
                    :src="img"
                    alt=""
                    class="absolute w-full h-full object-cover"
                    x-show="currentIndex === index"
                    x-transition:enter="transition-opacity duration-500"
                    x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100"
                    x-transition:leave="transition-opacity duration-500"
                    x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0"
                >
            </template>
        </div>

        <!-- Переключатели -->
        <div class="flex items-center mx-auto mt-4">
            <x-heroicon-o-chevron-left
                class="w-12 transition duration-100 hover:scale-110 cursor-pointer"
                @click="prev"
            />
            <div class="h-[2px] w-56 bg-black-400 relative">
                <div
                    class="h-1 bg-brown-300 absolute -top-[1px] transition-all duration-500"
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
    <div class="max-w-3xl flex flex-col">
        <h2 class="mb-4" x-ref="titleEl"></h2>
        <p x-ref="descEl"></p>
        <div class="flex gap-4 mt-8">
            <x-ui.link>Купить на Amazon</x-ui.link>
            <x-ui.link>Купить на Amazon</x-ui.link>
        </div>
    </div>
</section>


@push('page-js')
    <script src="https://cdn.jsdelivr.net/npm/animejs@3.2.1/lib/anime.min.js"></script>
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

                init() {
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

