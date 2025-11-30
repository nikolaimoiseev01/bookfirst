<main class="flex-1 content mb-32">
    @section('title')
        Собственная Книга
    @endsection
    <section class="mb-24 flex gap-12 lg:flex-col lg:text-center lg:items-center">
        <div class="w-60 min-w-60">
            <x-book3d cover="/fixed/own-book-example.jpg" class=""/>
        </div>
        <div class="flex flex-col gap-4 lg:items-center">
            <h3 class="border-b border-b-dark-600 w-fit">Издать собственную книгу</h3>
            <p>Кроме составления различных литературных сборников мы также предлагаем составить Вашу собственную книгу.
                Мы возьмем весь процесс на себя, начиная от верстки, проверки текста, составления содержания, и
                заканчивая регистрацией книги, присвоения ей уникального номера ISBN, а также ее размещение на нашем портале и соц. сетях.</p>
            <a href="{{route('portal.own_books.released')}}" wire:navigate
               class="flex gap-4 border text-xl border-dark-100 rounded px-4 py-2 w-fit hover:bg-green-500 hover:text-white transition">
                Наши изданные книги
            </a>
        </div>
        <div class="container flex flex-col w-fit px-4 h-fit">
            @foreach($info as $key => $value)
                <div class="border-b border-b-dark-100 py-4">
                    <p class="font-normal text-nowrap text-xl">{{$key}}: <span class="font-light">{{$value}}</span></p>
                </div>
            @endforeach
            <x-ui.link data-check-logged href="{{route('account.own_book.create')}}" class="my-4 py-2 font-medium !text-2xl tracking-wide">Начать издание!</x-ui.link>
        </div>
    </section>
    <section x-data="{ tab: '{{$tabs['default']}}' }"
             class="container p-4 transition-all min-w-full" >
        <nav class="flex flex-wrap md:justify-center md:flex-col gap-8 md:gap-4 text-4xl relative z-[1]
                                after:absolute after:left-0 after:right-0 after:bottom-0 after:block
                                after:w-full after:h-[2px] after:z-10 after:bg-dark-100 after:rounded
                                after:m-auto after:content-['']
                                after:transition-all after:duration-400">
            @foreach($tabs['tabs'] as $key => $value)
                <button @click="tab = '{{$key}}'"
                        :class="tab === '{{$key}}' ? 'text-green-500 after:w-full' : 'text-dark-100'"
                        class="cursor-pointer transition
                                relative  pb-4
                                after:absolute after:left-0 after:right-0 after:bottom-0 after:block
                                after:w-0 after:h-[2px] after:z-20 after:bg-green-500 after:rounded
                                after:m-auto after:content-['']
                                after:transition-all after:duration-400
                                hover:text-green-500
                        "
                >{{$value}}</button>
            @endforeach
            <a href="" class="text-dark-100  pb-4 hover:text-green-500 ml-auto md:mx-auto">Инструкция</a>
        </nav>
        <section
            x-show="tab === 'process'"
            class="px-12 py-8 pb-4 relative mx-auto">
            <x-bi-chevron-compact-left id="processPrev"
                                       class="[&.swiper-button-disabled]:opacity-30 [&.swiper-button-disabled]:cursor-not-allowed absolute w-12 h-auto top-1/2 -translate-y-1/2 left-0 cursor-pointer hover:scale-110 transition"/>
            <x-bi-chevron-compact-right id="processNext"
                                        class="[&.swiper-button-disabled]:opacity-30 [&.swiper-button-disabled]:cursor-not-allowed absolute w-12 h-auto top-1/2 -translate-y-1/2 right-0 cursor-pointer hover:scale-110 transition"/>
            <div class="swiper processSwiper w-full">
                <div class="swiper-wrapper">
                    @foreach($process as $el)
                        <div class="swiper-slide">
                            <p class="text-green-500 mb-2">{{$el['title']}}</p>
                            <p>{!! $el['text'] !!}</p>
                        </div>
                    @endforeach
                </div>
                @push('scripts')
                    <script type="module">
                        var swiper = new Swiper(".processSwiper", {
                            slidesPerView: 1,
                            spaceBetween: 30,
                            autoHeight: true,
                            grabCursor: true,
                            pagination: {
                                el: ".swiper-pagination",
                                clickable: true,
                            },
                            breakpoints: {
                                1280: {
                                    slidesPerView: 2,
                                }
                            },
                            navigation: {
                                nextEl: "#processNext",
                                prevEl: "#processPrev",
                            },
                        });
                    </script>
                @endpush
            </div>
        </section>

        <section class="" x-show="tab === 'calculator'">
            <livewire:components.portal.calculator-own-book/>
        </section>

    </section>
</main>
