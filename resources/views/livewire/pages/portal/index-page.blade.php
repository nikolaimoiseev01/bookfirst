<main>
    @section('title')
        Главная
    @endsection
    <x-video-modal/>
    <section class="h-screen lg:h-auto lg:mb-32 w-full flex items-center">
        <svg class="absolute xl:hidden left-0 top-0 h-full" id="Слой_1" data-name="Слой 1"
             xmlns="http://www.w3.org/2000/svg"
             viewBox="0 0 531.37 733.2">
            <path
                d="M0,0V733.2c160.82-13.53,151.4-116.91,245-168.7C561,453.3,564.65,307.64,494.5,213,454.77,159.4,298.39,0,270.8,0Z"
                class="fill-[#fffbef] dark:fill-[#292929]"/>
        </svg>
        <svg class="absolute xl:hidden right-0 max-h-[70vh] top-1/2 -translate-y-1/2" id="Слой_1"
             data-name="Слой 1"
             xmlns="http://www.w3.org/2000/svg" viewBox="0 0 681 673">
            <path
                d="M681,11.62c-129.23-71.85-326.93,211.67-559.88,246C-93.35,289.19,19.74,596.9,133.83,632c52.17,16,138.49,40.92,270.78,41C561.66,673.16,681,542.33,681,542.33Z"
                class="fill-[#fffbef] dark:fill-[#292929]"/>
        </svg>
        <div
            class="max-w-(--breakpoint-3xl) mx-auto w-[90%] gap-16 flex lg:flex-col lg:content justify-between items-center relative">
            <div class="flex flex-col gap-4 lg:max-w-auto lg:w-full lg:text-center w-1/2 min-w-1/2">
                <p class="hidden md:block">Независимое издательство<br>Первая Книга</p>
                <h2 class="text-7xl font-medium text-black-500 dark:text-white text-nowrap md:text-5xl">Ваш шаг в мир<br>литературы
                </h2>
                <x-portal.welcome-running-line/>
                <div class="flex flex-col gap-4 lg:items-center">
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
                    <x-ui.link-simple :isLivewire="false" href="{{route('social.index')}}" class="text-3xl !text-blue-500 !hover:text-blue-600 font-normal  text-nowrap">Перейти в социальную сеть
                    </x-ui.link-simple>
                </div>

            </div>
            <div class="flex-1 xl:w-full xl:!max-w-[90%]">
                <img src="{{$women['welcome']}}" class="max-w-3xl ml-auto w-full lg:!max-w-[80%] lg:!mx-auto" alt="">
            </div>

        </div>
    </section>

    <x-portal.collection-examples/>

    <section class="flex w-full">
        <img src="{{$women['sitting']}}" class="w-96 lg:hidden 2xl:w-80" alt="">
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
                    <x-book3d :cover="'/fixed/own-book-example.png'" class=" left-0 bottom-0"/>
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

    <section class="content mb-24">
        <div class="relative overflow-hidden rounded-2xl border border-green-100 bg-white px-6 py-6 shadow-sm dark:border-green-900 dark:bg-dark_bg md:px-4 md:py-5">
            <div class="relative grid grid-cols-[0.85fr_1.15fr] items-center gap-6 lg:grid-cols-1">
                <div class="flex flex-col items-start gap-3 lg:items-center lg:text-center">
                    <span class="inline-flex items-center gap-2 rounded-full bg-green-100 px-3 py-1 text-sm font-semibold text-green-700 dark:bg-green-900/50 dark:text-green-300">
                        <x-heroicon-o-sparkles class="h-4 w-4"/>
                        Новое на сайте
                    </span>
                    <div>
                        <h2 class="text-3xl font-semibold leading-tight lg:text-2xl">Идея есть — ИИ поможет её воплотить</h2>
                        <p class="mt-2 max-w-xl text-lg">Напишите поздравление, продолжите начатое произведение или создайте иллюстрацию. Выберите задачу и получите результат за несколько секунд.</p>
                    </div>
                    <div class="flex flex-wrap gap-2 lg:justify-center">
                        <span class="rounded-full border border-green-100 bg-green-50 px-3 py-1 text-sm text-green-800 dark:border-green-900 dark:bg-green-900/30 dark:text-green-200">Поздравление другу</span>
                        <span class="rounded-full border border-green-100 bg-green-50 px-3 py-1 text-sm text-green-800 dark:border-green-900 dark:bg-green-900/30 dark:text-green-200">Продолжение рассказа</span>
                        <span class="rounded-full border border-green-100 bg-green-50 px-3 py-1 text-sm text-green-800 dark:border-green-900 dark:bg-green-900/30 dark:text-green-200">Иллюстрация к книге</span>
                    </div>
                    <a wire:navigate data-check-logged href="{{ route('account.ai-assistant') }}"
                       class="inline-flex items-center gap-2 rounded-lg bg-green-600 px-5 py-2.5 font-semibold text-white shadow-sm transition hover:bg-green-700 hover:shadow-md">
                        Попробовать ИИ-помощника
                        <x-heroicon-o-arrow-right class="h-5 w-5"/>
                    </a>
                    <p class="text-lg text-green-600 font-medium">Бесплатные попытки доступны после регистрации</p>
                </div>

                <div class="grid w-full grid-cols-2 gap-3 sm:grid-cols-1">
                    <article class="rounded-2xl border border-gray-100 bg-gray-50 p-4 shadow-sm dark:border-gray-700 dark:bg-white/5">
                        <div class="mb-4 flex items-center gap-3 border-b border-gray-200 pb-3 dark:border-gray-700">
                            <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-green-100 text-green-600 dark:bg-green-900/50 dark:text-green-300">
                                <x-heroicon-o-pencil-square class="h-5 w-5"/>
                            </div>
                            <div>
                                <p class="font-semibold">ИИ-помощник</p>
                                <p class="text-sm font-normal">Поможет с текстом</p>
                            </div>
                            <span class="ml-auto flex items-center gap-1.5 text-xs text-green-600">
                                <span class="h-2 w-2 animate-pulse rounded-full bg-green-500"></span>
                                в работе
                            </span>
                        </div>
                        <div class="ml-auto max-w-[88%] rounded-2xl rounded-br-sm bg-green-600 px-4 py-3 text-base leading-relaxed text-white shadow-sm">
                            Придумай тёплое поздравление другу, который любит книги
                        </div>
                        <div class="mt-3 flex gap-3 rounded-2xl rounded-bl-sm bg-white p-3 dark:bg-dark_bg">
                            <x-heroicon-o-sparkles class="mt-0.5 h-5 w-5 shrink-0 text-green-600"/>
                            <div class="flex flex-1 flex-col gap-2">
                                <span class="h-2 w-4/5 animate-pulse rounded-full bg-green-200 dark:bg-green-800"></span>
                                <span class="h-2 w-full animate-pulse rounded-full bg-gray-200 dark:bg-gray-600"></span>
                                <span class="h-2 w-3/4 animate-pulse rounded-full bg-gray-200 dark:bg-gray-600"></span>
                                <div class="mt-1 flex items-center gap-1.5 text-xs text-dark-200">
                                    <span>Создаёт ответ</span>
                                    <span class="h-1.5 w-1.5 animate-bounce rounded-full bg-green-500"></span>
                                    <span class="h-1.5 w-1.5 animate-bounce rounded-full bg-green-500 [animation-delay:120ms]"></span>
                                    <span class="h-1.5 w-1.5 animate-bounce rounded-full bg-green-500 [animation-delay:240ms]"></span>
                                </div>
                            </div>
                        </div>
                    </article>

                    <article class="rounded-2xl border border-gray-100 bg-gray-50 p-4 shadow-sm dark:border-gray-700 dark:bg-white/5">
                        <div class="mb-3 flex items-center gap-2 border-b border-gray-200 pb-3 dark:border-gray-700">
                            <x-heroicon-o-photo class="h-5 w-5 text-green-600"/>
                            <span class="font-semibold">Создание картинки</span>
                        </div>
                        <p class="mb-3 font-medium rounded-xl rounded-tl-sm bg-green-100 px-3 py-2 text-base leading-relaxed text-green-800 dark:bg-green-900/40 dark:text-green-100">
                            Уютная книжная иллюстрация с рыжим котом у окна
                        </p>
                        <img src="/fixed/ai-assistant-example.png" alt="Рыжий кот у окна на фоне книжного города, созданный ИИ"
                             loading="lazy" class="aspect-[4/3] w-full rounded-xl object-cover shadow-sm">
                        <p class="mt-2 flex items-center gap-1.5 text-xs font-medium text-green-700 dark:text-green-300">
                            <x-heroicon-o-check-circle class="h-4 w-4"/>
                            Иллюстрация готова
                        </p>
                    </article>
                </div>
            </div>
        </div>
    </section>

    <x-portal.own-books-index-slider/>

    <x-portal.reviews-portal-index/>
</main>
