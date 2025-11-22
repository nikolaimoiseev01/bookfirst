<section id="reviews" class="content mb-32">
    <div class="flex w-full justify-between items-center mb-8">
        <h2>Отзывы</h2>
        <x-ui.link-simple :isLivewire="false" target="_blank" class="text-3xl" href="https://vk.com/topic-122176261_35858257">Больше отзывов</x-ui.link-simple>
    </div>
    <div class="flex justify-between gap-8 mb-32">
        @foreach($reviews as $review)
            <div class="flex flex-col max-w-96">
                <div class="relative mb-8 p-4 container flex flex-col gap-2">
                    <svg class="absolute w-[50px] h-[50px] -bottom-[35px]"
                         xmlns="http://www.w3.org/2000/svg"
                         xmlns:xlink="http://www.w3.org/1999/xlink"
                         viewBox="0 0 26.4 16.08">
                        <defs>
                            <style>.cls-1 {
                                    isolation: isolate;
                                }

                                .cls-2 {
                                    opacity: 0.06;
                                    mix-blend-mode: color-burn;
                                }

                                .cls-3 {
                                    fill: #fff;
                                }</style>
                        </defs>
                        <g class="cls-1">
                            <g id="Слой_1" data-name="Слой 1">
                                <image class="cls-2" width="110" height="67" transform="scale(0.24)"
                                       xlink:href="data:image/png;base64,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"></image>
                                <path class="cls-3"
                                      d="M0,10H20l-9.56,9.55a.62.62,0,0,1-.5.23.63.63,0,0,1-.43-.21Z"
                                      transform="translate(3.24 -7.8)"></path>
                            </g>
                        </g>
                    </svg>
                    <p class="text-xl">{!! $review['text'] !!}</p>
                </div>
                <div class="flex items-center gap-4">
                    <img src="{{$review['avatar']}}" class="w-16 h-16 rounded-full" alt="">
                    <x-ui.link-simple
                        href="{{route('social.user', $review['user_id'])}}">{{$review['name']}}</x-ui.link-simple>
                </div>
            </div>
        @endforeach
    </div>

    <div class="flex w-full justify-between mb-8">
        <h2>Фото наших авторов</h2>
    </div>

    <style>
        .swiper-button-disabled {
            opacity: 0.6
        }
    </style>
    <div class="w-full px-16 relative flex gap-4">
        <x-heroicon-o-chevron-left
            class="w-12 absolute left-0 top-1/2 -translate-y-1/2 transition duration-100 hover:scale-110 cursor-pointer"
            id="photoSliderPrev"
        />
        <div class="swiper photoSlider">
            <div class="swiper-wrapper">
                @for($i=1; $i<=17; $i++)
                    <div class="swiper-slide !w-fit container p-4">
                        <img src="/fixed/review_imgs/{{$i}}.jpg" class="h-96 object-cover rounded-xl" alt="">
                    </div>
                @endfor
            </div>
            <div class="swiper-pagination"></div>
        </div>
        <x-heroicon-o-chevron-right
            class="w-12 absolute right-0 top-1/2 -translate-y-1/2 transition duration-100 hover:scale-110 cursor-pointer"
            id="photoSliderNext"
        />
    </div>


    @push('scripts')
        <script type="module">
            var swiper = new Swiper(".photoSlider", {
                slidesPerView: 'auto',
                spaceBetween: 30,
                navigation: {
                    nextEl: "#photoSliderNext",
                    prevEl: "#photoSliderPrev",
                },
                loop: true
            });
        </script>
    @endpush
</section>
