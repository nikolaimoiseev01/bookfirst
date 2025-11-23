<main class="flex-1 content mb-32">
    @section('title')
        {{$collection['title']}}
    @endsection
    <nav class="flex gap-2 mb-12 flex-wrap">
        <x-ui.link-simple href="{{route('portal.collections.actual')}}">Сборники</x-ui.link-simple>
        @if($collection['status'] == \App\Enums\CollectionStatusEnums::APPS_IN_PROGRESS)
            <p>/</p>
            <x-ui.link-simple href="{{route('portal.collections.actual')}}">Идет приём заявок</x-ui.link-simple>
        @endif
        <p>/</p>
        <p>{{$collection['title']}}</p>
    </nav>
    <section class="mb-24 flex gap-12 lg:flex-col lg:text-center lg:items-center">
        <div class="w-60 min-w-60">
            <x-book3d :cover="$collection->getFirstMediaUrl('cover_front')" class=""/>
        </div>
        <div class="flex flex-col gap-4 lg:items-center">
            <h3 class="border-b border-b-dark-600 w-fit">{{$collection['title']}}</h3>
            <p>{{$collection['description']}}</p>
            <h4>Приобрести книгу:</h4>
            <div class="flex flex-wrap gap-8 md:gap-4 md:flex-col md:justify-center">
                @foreach($collection['selling_links'] ?? [] as $name => $link)
                    <a href="{{$link}}" target="_blank" class="flex gap-4 border text-xl border-dark-100 rounded px-4 py-2 hover:bg-green-500 hover:text-white transition">
                        <img src="/fixed/logo-{{$name}}.png" class="w-16" alt="">
                        {{$name}}
                    </a>
                @endforeach
                <a href="" data-check-logged target="_blank" class="flex gap-4 border text-xl border-dark-100 rounded px-4 py-2 hover:bg-green-500 hover:text-white transition">
                    Электронная версия (100 руб.)
                </a>
            </div>
        </div>
        <div class="container flex flex-col w-fit px-4 h-fit">
            @foreach($info as $key => $value)
                <div class="border-b border-b-dark-100 py-4">
                    <p class="font-normal text-nowrap text-xl">{{$key}}: <span class="font-light">{{$value}}</span></p>
                </div>
            @endforeach
            @if($collection['status'] == \App\Enums\CollectionStatusEnums::APPS_IN_PROGRESS)
                <x-ui.link href="{{route('account.participation.create', $collection['id'])}}" data-check-logged class="my-4 py-2 font-medium !text-2xl tracking-wide">Принять участие!</x-ui.link>
            @else
                <p class="my-4 text-center text-red-300 font-normal">Прием заявок окончен</p>
            @endif
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
            <a wire:navigate href="{{route('portal.help.collection')}}" class="text-dark-100  pb-4 hover:text-green-500 ml-auto md:mx-auto">Инструкция</a>
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
                            autoHeight: true,
                            spaceBetween: 30,
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
            <livewire:components.portal.calculator-collection/>
        </section>

        <section x-show="tab === 'dates'"
                 class="flex relative w-full mx-auto pt-4"
        >
            <x-bi-chevron-compact-left id="datesPrev"
                                       class="[&.swiper-button-disabled]:opacity-30 [&.swiper-button-disabled]:cursor-not-allowed absolute w-12 h-auto top-1/2 -translate-y-1/2 -left-2 cursor-pointer hover:scale-110 transition"/>
            <x-bi-chevron-compact-right id="datesNext"
                                        class="[&.swiper-button-disabled]:opacity-30 [&.swiper-button-disabled]:cursor-not-allowed absolute w-12 h-auto top-1/2 -translate-y-1/2 -right-2 cursor-pointer hover:scale-110 transition"/>
            <div class="swiper datesSwiper w-[90%] max-w-8xl !overflow-y-visible !overflow-x-clip">
                <div class="swiper-wrapper">
                    @foreach($dates as $key=>$date)
                        <div
                            class="swiper-slide border-r py-8 px-16 border-dark-100 min-w-80 md:min-w-full md:w-full justify-center items-center md:border-none text-center relative flex-1">
                            <p class="text-4xl font-normal text-dark-400 mb-2">{{$date['date']}}</p>
                            <p class="text-dark-400">{{$date['desc']}}</p>
                            @if($date['tooltip'] ?? null)
                                <x-ui.question-mark direction="left" class="!absolute bottom-4 right-4">
                                    {{$date['tooltip']}}
                                </x-ui.question-mark>
                            @endif
                            {{--                            <x-bi-arrow-right--}}
                            {{--                                class="absolute top-1/2 text-dark-100 -right-8 w-12 h-auto -translate-y-1/2"/>--}}
                        </div>
                    @endforeach
                </div>
                @push('scripts')
                    <script type="module">
                        var swiper = new Swiper(".datesSwiper", {
                            slidesPerView: 1,
                            spaceBetween: 0,
                            grabCursor: true,
                            pagination: {
                                el: ".swiper-pagination",
                                clickable: true,
                            },
                            navigation: {
                                nextEl: "#datesNext",
                                prevEl: "#datesPrev",
                            },
                            breakpoints: {
                                767: {
                                    slidesPerView: 'auto',
                                }
                            },
                        });
                    </script>
                @endpush
            </div>
        </section>

        <section x-show="tab === 'free_participation'"
                 class="p-8 pb-4 flex flex-col gap-4"
        >
            <h3 class="text-3xl mx-auto w-fit">Объявлен <span class="text-green-500">КОНКУРС</span> среди участников
                сборника!</h3>
            <p>Участие в данном сборнике может быть бесплатным именно для Вас!</p>
            <div class="flex gap-4 md:flex-col">
                <div class="w-1/2 md:w-full">
                    <p class="text-3xl font-normal">Правила конкурса:</p>
                    <p>
                        Каждый включенный в сборник автор автоматически становится участником конкурса. (порядок
                        участия).
                        В период предварительной проверки авторам предоставляется возможность проголосовать за
                        понравившиеся
                        произведения. Опираясь на голоса авторов, наша команда подводит итоги конкурса и объявляет
                        победителей в <a href="https://vk.com/yourfirstbook">нашей группе ВК</a></p>
                </div>
                <div class="w-1/2 md:w-full">
                    <p class="text-3xl font-normal">Призы:</p>
                    <p><span class="text-green text-green-500 font-medium">1 место:</span> Бесплатное участие, печатный
                        экземпляр сборника и пересылка</p>
                    <p><span class="text-green text-green-500 font-medium">2 место:</span> Половина стоимости участия и
                        50% промокод для участия в следующем сборнике</p>
                    <p><span class="text-green text-green-500 font-medium">3 место:</span> Бесплатный печатный экземпляр
                        и пересылка</p>
                </div>
            </div>
            <p class="italic">*Подробная информация о правилах получения будет предоставлена призеру лично.</p>
        </section>

        <section x-show="tab === 'read_part'" class="p-4">
            <iframe src="{{$collection->getFirstMediaUrl('inside_file_preview')}}"
                    width="100%" height="600px"></iframe>
        </section>
    </section>
</main>
