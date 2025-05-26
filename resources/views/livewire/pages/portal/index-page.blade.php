<main>
    <section class="h-screen w-full flex items-center">
        <svg class="absolute left-0 top-0 h-full" id="Слой_1" data-name="Слой 1" xmlns="http://www.w3.org/2000/svg"
             viewBox="0 0 531.37 733.2">
            <path
                d="M0,0V733.2c160.82-13.53,151.4-116.91,245-168.7C561,453.3,564.65,307.64,494.5,213,454.77,159.4,298.39,0,270.8,0Z"
                class="fill-[#fffbef] dark:fill-[#292929]"/>
        </svg>
        <svg class="absolute right-0 max-h-[70vh] top-1/2 -translate-y-1/2" id="Слой_1" data-name="Слой 1"
             xmlns="http://www.w3.org/2000/svg" viewBox="0 0 681 673">
            <path
                d="M681,11.62c-129.23-71.85-326.93,211.67-559.88,246C-93.35,289.19,19.74,596.9,133.83,632c52.17,16,138.49,40.92,270.78,41C561.66,673.16,681,542.33,681,542.33Z"
                class="fill-[#fffbef] dark:fill-[#292929]"/>
        </svg>
        <div class="max-w-screen-3xl mx-auto w-[90%] flex justify-between items-center relative">
            <div class="flex flex-col gap-4 max-w-[600px]">
                <h1 class="text-7xl font-medium text-black-500 dark:text-white">Ваш шаг в мир<br>литературы</h1>
                <x-portal.welcome-running-line/>
                <div class="flex gap-8">
                    <a href="#"
                       class="text-3xl px-10 py-1 text-green-500
                              border border-green-500 no-underline
                              relative overflow-hidden rounded-[10px]
                              transition-all duration-300
                              hover:shadow-[5px_5px_2px_#499b897a] hover:scale-[1.03]">
                        Опубликовать
                    </a>
                </div>
            </div>
            <img src="/fixed/woman_welcome.svg" class="max-w-3xl" alt="">
        </div>
    </section>

    <x-portal.collection-examples/>

    <section class="flex w-full">
        <img src="/fixed/woman_sitting.svg" class="w-96" alt="">
        <div class="flex flex-col mb-4 w-full ml-32">
            <h2 class="mb-8">За <span class="text-green-500">{{ date('Y') - 2015 }} лет</span> работы у нас:</h2>
            <x-portal.history-numbers/>
        </div>
    </section>

    <section class="content">
        <div class="relative w-fit mx-auto">
            <svg class="absolute -top-[100px]" xmlns="http://www.w3.org/2000/svg" width="401" height="278" viewBox="0 0 401 278" fill="none">
                <script xmlns=""/>
                <path
                    d="M56.385 83.5782C162.464 106.477 137.685 49.9122 241.152 77.093C344.619 104.274 386.775 169.962 371.842 207.864C356.909 245.766 274.186 248.669 187.075 214.349C99.9639 180.028 -49.6943 60.6796 56.385 83.5782Z"
                    stroke="#73A096" stroke-width="2" stroke-linecap="round" stroke-dasharray="10 15"/>
                <script xmlns=""/>
            </svg>
            <h2>Идет прием заявок</h2>
        </div>
        <div class="flex flex-col gap-8">
        @foreach($collections_actual as $collection)
            <div class="container flex gap-4">
                <img src="{{$collection->getFirstMediaUrl('cover_2d')}}" alt="">


                <div class="flex flex-col gap-4">
                    <h3>{{$collection['name']}}</h3>
                    <p>{{$collection['description']}}</p>
                </div>
                <div class="flex flex-col">
                    <x-ui.link>Подробнее</x-ui.link>
                    <x-ui.link>Принять участие</x-ui.link>
                </div>
            </div>
        @endforeach
        </div>
    </section>
</main>
