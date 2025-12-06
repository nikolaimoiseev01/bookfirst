<main class="">
    @section('title')
        Соц. сеть
    @endsection
    <section class="h-screen w-full flex items-center mb-16 lg:h-auto">
        <svg class="absolute left-0 top-0 h-full lg:hidden" id="Слой_1" data-name="Слой 1"
             xmlns="http://www.w3.org/2000/svg"
             viewBox="0 0 531.37 733.2">
            <path
                d="M0,0V733.2c160.82-13.53,151.4-116.91,245-168.7C561,453.3,564.65,307.64,494.5,213,454.77,159.4,298.39,0,270.8,0Z"
                class="fill-[#F8F2EF] dark:fill-[#F8F2EF]"/>
        </svg>
        <svg class="absolute right-0 max-h-[70vh] top-1/2 -translate-y-1/2 lg:hidden" id="Слой_1"
             data-name="Слой 1"
             xmlns="http://www.w3.org/2000/svg" viewBox="0 0 681 673">
            <path
                d="M681,11.62c-129.23-71.85-326.93,211.67-559.88,246C-93.35,289.19,19.74,596.9,133.83,632c52.17,16,138.49,40.92,270.78,41C561.66,673.16,681,542.33,681,542.33Z"
                class="fill-[#F8F2EF] dark:fill-[#F8F2EF]"/>
        </svg>
        <div
            class="max-w-(--breakpoint-3xl) mx-auto w-[90%] flex lg:flex-col gap-16 justify-between items-center relative lg:pt-16">
            <div class="w-1/2 lg:w-full lg:order-2">
                <img src="/fixed/mascots/woman_social_welcome.svg" class="max-w-3xl lg:mx-auto" alt="">
            </div>

            <div class="flex flex-col gap-16 max-w-1/2 pl-32 lg:w-full lg:pl-0 lg:max-w-full lg:justify-center lg:text-center">
                <h2 class="text-7xl md:text-6xl font-medium text-black-500 dark:text-white">Еще и просто
                    социальная сеть</h2>
                <div class="flex gap-y-2 gap-x-8 text-blue-500  flex-wrap lg:mx-auto lg:justify-center lg:gap-8">
                    <h3 class="text-3xl "># Создавай</h3>
                    <h3 class="text-3xl "># Публикуй</h3>
                    <h3 class="text-3xl "># Вдохновляй</h3>
                    <h3 class="text-3xl "># Общайся</h3>
                </div>

                <div class="flex gap-8 mt-auto lg:mx-auto">
                    <a data-check-logged href="{{route('account.works')}}"
                       class="text-3xl px-10 py-1 text-blue-500
                              border border-blue-500 no-underline
                              relative overflow-hidden rounded-[10px]f
                              transition-all duration-300
                              hover:shadow-[5px_5px_2px_#4a96d77a] hover:scale-[1.03]">
                        Опубликовать
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{--    <x-portal.index-social-works-slider/>--}}
    <section class="content mb-16" x-data="{tab: 'works'}">
        <div class="flex flex-col max-w-6xl mx-auto">
            <div class="flex justify-between mb-16 md:flex-col md:items-center md:text-center gap-4 md:mb-8">
                <div class="relative w-fit">
                    <svg x-show="tab == 'works'" class="absolute -top-[100px] lg:hidden"
                         xmlns="http://www.w3.org/2000/svg"
                         width="401" height="278"
                         viewBox="0 0 401 278" fill="none">
                        <script xmlns=""/>
                        <path
                            d="M56.385 83.5782C162.464 106.477 137.685 49.9122 241.152 77.093C344.619 104.274 386.775 169.962 371.842 207.864C356.909 245.766 274.186 248.669 187.075 214.349C99.9639 180.028 -49.6943 60.6796 56.385 83.5782Z"
                            class="stroke-blue-500" stroke-width="2" stroke-linecap="round"
                            stroke-dasharray="10 15"/>
                        <script xmlns=""/>
                    </svg>
                    <h2 :class="tab == 'authors' ? 'text-gray-400 transition hover:text-blue-500 cursor-pointer' : 'text-blue-500'"
                        @click="tab = 'works'">Лента произведений</h2>
                </div>
                <div class="relative w-fit">
                    <svg x-show="tab == 'authors'" class="absolute -top-[100px] lg:hidden"
                         xmlns="http://www.w3.org/2000/svg"
                         width="401" height="278"
                         viewBox="0 0 401 278" fill="none">
                        <script xmlns=""/>
                        <path
                            d="M56.385 83.5782C162.464 106.477 137.685 49.9122 241.152 77.093C344.619 104.274 386.775 169.962 371.842 207.864C356.909 245.766 274.186 248.669 187.075 214.349C99.9639 180.028 -49.6943 60.6796 56.385 83.5782Z"
                            class="stroke-blue-500" stroke-width="2" stroke-linecap="round"
                            stroke-dasharray="10 15"/>
                        <script xmlns=""/>
                    </svg>
                    <h2 :class="tab == 'works' ? 'text-gray-400 transition hover:text-blue-500 cursor-pointer' : 'text-blue-500'"
                        @click="tab = 'authors'">Наши авторы</h2>
                </div>
            </div>
            <div x-show="tab=='works'">
                <livewire:components.social.work-feed/>
            </div>
            <div x-show="tab=='authors'" class="flex flex-col gap-8">
                <div class="flex flex-wrap gap-8 justify-center">
                    @foreach($randomAuthors as $author)
                        <x-ui.cards.author-card :author="$author"/>
                    @endforeach
                </div>
                <x-ui.link-simple class="mx-auto" wire:click="updateAuthors" wire:loading.remove>
                    Обновить случайный список
                </x-ui.link-simple>
                <x-ui.spinner class="w-8 mx-auto" wire:loading/>
            </div>
        </div>
    </section>
</main>
