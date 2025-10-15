<main class="">
    @section('title')
        Соц. сеть
    @endsection
    <section class="h-screen w-full flex items-center">
        <svg class="absolute left-0 top-0 h-full" id="Слой_1" data-name="Слой 1" xmlns="http://www.w3.org/2000/svg"
             viewBox="0 0 531.37 733.2">
            <path
                d="M0,0V733.2c160.82-13.53,151.4-116.91,245-168.7C561,453.3,564.65,307.64,494.5,213,454.77,159.4,298.39,0,270.8,0Z"
                class="fill-[#F8F2EF] dark:fill-[#F8F2EF]"/>
        </svg>
        <svg class="absolute right-0 max-h-[70vh] top-1/2 -translate-y-1/2" id="Слой_1" data-name="Слой 1"
             xmlns="http://www.w3.org/2000/svg" viewBox="0 0 681 673">
            <path
                d="M681,11.62c-129.23-71.85-326.93,211.67-559.88,246C-93.35,289.19,19.74,596.9,133.83,632c52.17,16,138.49,40.92,270.78,41C561.66,673.16,681,542.33,681,542.33Z"
                class="fill-[#F8F2EF] dark:fill-[#F8F2EF]"/>
        </svg>
        <div class="max-w-(--breakpoint-3xl) mx-auto w-[90%] flex justify-between items-center relative">
            <div class="w-1/2">
                <img src="/fixed/woman_social_welcome.svg" class="max-w-3xl" alt="">
            </div>

            <div class="flex flex-col gap-16 max-w-1/2 pl-32">
                <h2 class="text-7xl font-medium text-black-500 dark:text-white">Еще и просто социальная сеть</h2>
                <div class="flex gap-y-2 gap-x-8 text-blue-500  flex-wrap">
                    <h3 class="text-3xl"># Создавай</h3>
                    <h3 class="text-3xl"># Публикуй</h3>
                    <h3 class="text-3xl"># Вдохновляй</h3>
                    <h3 class="text-3xl"># Общайся</h3>
                </div>

                <div class="flex gap-8 mt-auto">
                    <a href="#"
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

    <x-portal.index-social-works-slider/>
</main>
