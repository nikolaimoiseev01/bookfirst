<main>
    @section('title')
        Главная
    @endsection
    <x-video-modal/>
    <section class="h-screen w-full flex items-center">
        <svg class="absolute lg:hidden left-0 top-0 h-full" id="Слой_1" data-name="Слой 1"
             xmlns="http://www.w3.org/2000/svg"
             viewBox="0 0 531.37 733.2">
            <path
                d="M0,0V733.2c160.82-13.53,151.4-116.91,245-168.7C561,453.3,564.65,307.64,494.5,213,454.77,159.4,298.39,0,270.8,0Z"
                class="fill-[#fffbef] dark:fill-[#292929]"/>
        </svg>
        <svg class="absolute lg:hidden right-0 max-h-[70vh] top-1/2 -translate-y-1/2" id="Слой_1"
             data-name="Слой 1"
             xmlns="http://www.w3.org/2000/svg" viewBox="0 0 681 673">
            <path
                d="M681,11.62c-129.23-71.85-326.93,211.67-559.88,246C-93.35,289.19,19.74,596.9,133.83,632c52.17,16,138.49,40.92,270.78,41C561.66,673.16,681,542.33,681,542.33Z"
                class="fill-[#fffbef] dark:fill-[#292929]"/>
        </svg>
        <div
            class="max-w-(--breakpoint-3xl) mx-auto w-[90%] gap-16 flex lg:flex-col lg:content justify-between items-center relative">
            <div class="flex flex-col gap-4 lg:max-w-auto lg:w-full lg:text-center max-w-[600px]">
                <h2 class="text-7xl font-medium text-black-500 dark:text-white text-nowrap md:text-5xl">Ваш шаг в мир<br>литературы
                </h2>
                <x-portal.welcome-running-line/>
                <div class="flex gap-8 items-center lg:mx-auto lg:flex-wrap lg:justify-center">
                    <a href="#actual-collections"
                       class="text-3xl px-10 py-1 text-green-500 rounded-xl
                              border border-green-500 no-underline
                              relative overflow-hidden
                              transition-all duration-300
                              hover:shadow-[5px_5px_2px_#499b897a] hover:scale-[1.03]">
                        Опубликовать
                    </a>
                    <x-ui.link-simple @click="$dispatch('open-modal', 'videoModal')"
                                      class="text-3xl font-normal  text-nowrap">Как это работает
                    </x-ui.link-simple>
                </div>
            </div>
            <img src="/fixed/woman_welcome.svg" class="max-w-3xl  xl:max-w-1/2 md:!max-w-[90%] md:w-full" alt="">
        </div>
    </section>

    <x-portal.collection-examples/>

    <section class="flex w-full">
        <img src="/fixed/woman_sitting.svg" class="w-96 lg:hidden 2xl:w-80" alt="">
        <div class="flex flex-col mb-4 w-full ml-32 xl:ml-6 lg:w-[90%] lg:!mx-auto lg:!ml-auto">
            <h2 class="mb-8 lg:text-center">За <span class="  text-green-500">{{ date('Y') - 2015 }} лет</span>
                работы у нас:</h2>
            <x-portal.history-numbers/>
        </div>
    </section>

    <section id="actual-collections" class="content mb-52 pt-32">
        <div class="relative w-fit mx-auto mb-32">
            <svg class="absolute -top-[100px] md:hidden" xmlns="http://www.w3.org/2000/svg" width="401"
                 height="278"
                 viewBox="0 0 401 278" fill="none">
                <script xmlns=""/>
                <path
                    d="M56.385 83.5782C162.464 106.477 137.685 49.9122 241.152 77.093C344.619 104.274 386.775 169.962 371.842 207.864C356.909 245.766 274.186 248.669 187.075 214.349C99.9639 180.028 -49.6943 60.6796 56.385 83.5782Z"
                    stroke="#73A096" stroke-width="2" stroke-linecap="round"
                    stroke-dasharray="10 15"/>
                <script xmlns=""/>
            </svg>
            <h2>Идет прием заявок</h2>
        </div>
        <div class="flex flex-col gap-16">
            @foreach($collections_actual as $collection)
                <x-ui.cards.card-collection-wide :collection="$collection"/>
            @endforeach
            <div class="container flex gap-10 relative p-4 lg:flex-col lg:items-center md:pt-24 w-full max-w-full">
                <div
                    class="min-w-[180px] max-w-[180px]  md:min-w-[140px]  md:max-w-[140px] relative">
                    <x-book3d :cover="'/fixed/own-book-example.jpg'" class=" left-0 bottom-0"/>
                </div>
                <div class="flex flex-col gap-4 lg:items-center lg:text-center">
                    <h3>Ваша собственная книга</h3>
                    <p>Мы также предлагаем издать Вашу собственную книгу. Мы возьмем на себя весь
                        процесс, начиная от верстки, проверки текста, и заканчивая регистрацией
                        книги, присвоения ей уникального номера ISBN, а также ее размещение на
                        всемирных книжных интернет площадках (Amazon.com, Books.ru и т. д.).</p>
                </div>
                <div class="flex flex-col justify-center gap-4 lg:w-full">
                    <x-ui.link href="{{route('portal.own_book.application')}}">Подробнее</x-ui.link>
                    <x-ui.link data-check-logged href="{{route('account.own_book.create')}}">Подать
                        заявку
                    </x-ui.link>
                </div>
            </div>
        </div>
    </section>

    <x-portal.own-books-index-slider/>

    <x-portal.reviews-portal-index/>
</main>
