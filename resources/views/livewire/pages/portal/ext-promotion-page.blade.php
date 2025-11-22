<main class="flex-1 content mb-32">
    @section('title')
        Продвижение
    @endsection
    <section class="mb-24 flex gap-12 items-center lg:flex-col lg:text-center lg:items-center">
        <img src="/fixed/ext_promotion_icon.png" class="w-64 h-64" alt="">
        <div class="flex flex-col gap-4 lg:items-center">
            <h3 class="w-fit">Привлечение читателей на других сайтах</h3>
            <p>Кроме составления различных литературных сборников и издания книг, мы также предлагаем необычную услугу:
                продвижение вашего творчества на литературных интернет-порталах. Мы в десятки раз увеличиваем количество
                посетителей ваших авторских страниц. Данная услуга - это не литературное продвижение или раскрутка
                вашего творчества. Это расширение аудитории ваших произведений, реклама ваших творений. Всё остальное
                зависит лишь от вас и ваших произведений!</p>
        </div>
        <div class="container flex flex-col w-fit px-4 h-fit">
            @foreach($info as $key => $value)
                <div class="border-b border-b-dark-100 py-4">
                    <p class="font-normal text-nowrap text-xl">{{$key}}: <span class="font-light">{{$value}}</span></p>
                </div>
            @endforeach
            <x-ui.link href="{{route('account.ext_promotion.create')}}" data-check-logged class="my-4 py-2 font-medium !text-2xl tracking-wide">Подать заявку!</x-ui.link>
        </div>
    </section>
    <section x-data="{ tab: '{{$tabs['default']}}' }"
             class="container p-4 transition-all min-w-full">
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
            <livewire:components.portal.calculator-ext-promotion/>
        </section>
        <section class="p-8" x-show="tab === 'security'">
            <p class="mb-8">Учитывая, что для запуска услуги по привлечению читателей на любом сайте нам требуется
                пароль от
                авторской страницы, то вполне закономерно, что у наших клиентов возникает вопрос о безопасности передачи
                нам этого пароля, ведь мы получаем доступ к странице.</p>
            <p class="font-normal">Ниже приведены аргументы того, почему это безопасно:</p>
            <ul class="list-disc pl-6 text-2xl font-light">
                <li>Мы нигде не храним ваши логины и пароли. Все данные для авторизации (логин или адрес страницы, и
                    пароль) шифруются и потом используются нами в зашифрованном виде. Все письма, сообщения в соц. сетях
                    и т.п., в которых эти данные нам передали, мы удаляем безвозвратно.
                </li>
                <li>Получая полный доступ к вашей авторской странице, мы не совершаем никаких действий от вашего имени,
                    кроме тех, которые оговорены услугой и без которых невозможно привлечь читателей на страницу. В
                    частности, мы не пишем сообщения/рецензии/отзывы и др., мы не заказываем баллы/анонсы и т.д., не
                    общаемся с другими пользователями сайтов, не пользуемся никакими дополнительными платными или
                    бесплатными услугами сайтов от вашего имени. Это возможно только если вы сами нас об этом попросите
                    (например, в рамках дополнительной услуги по сопровождению страницы).
                </li>
                <li>Мы ни в коем случае не редактируем, не удаляем, не добавляем произведения.</li>
                <li>Мы категорически ничего не делаем в настройках страницы.
            </ul>
        </section>

    </section>
</main>
