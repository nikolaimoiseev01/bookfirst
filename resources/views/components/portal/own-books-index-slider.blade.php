<section id="ownBooks" class="content mb-32">

    <div class="flex w-full justify-between items-center mb-8 flex-wrap md:text-center md:justify-center gap-8">
        <h2>Книги наших авторов</h2>
        <x-ui.link-simple class="text-3xl" href="{{route('portal.own_books.released')}}">Все книги
        </x-ui.link-simple>
    </div>
    @if($mainOwnBook ?? null)
        <div class="mb-8 flex gap-12 lg:flex-col lg:text-center lg:items-center">
            <div class="w-60 min-w-60">
                <x-book3d cover="{{$mainOwnBook->getFirstMediaUrl('cover_front')}}" class=""/>
            </div>
            <div class="flex flex-col gap-4 lg:items-center w-full">
                <div class="flex flex-wrap gap-4 justify-between items-center w-full md:text-center md:justify-center">
                    <h3 class="border-b border-b-dark-600 w-fit line-clamp-1">{{$mainOwnBook['title']}}</h3>
                    <x-ui.tooltip-wrap
                        text="В большом блоке на главной странице портала книги появляются в рамках выбора 2-го варианта продвижения при оформлении заявки (в случайном порядке)">
                        <p class="italic text-green-500 text-xl cursor-help">Как оказаться в этом блоке</p>
                    </x-ui.tooltip-wrap>
                </div>

                <div class="flex gap-2 items-center md:text-center md:justify-center">
                    <img src="{{getUserAvatar($mainOwnBook->user)}}" class="w-8 h-8 rounded-full" alt="">
                    <x-ui.link-simple :isLivewire="false" target="_blank"
                                      href="{{route('social.user', ['id' => $mainOwnBook['user_id']])}}">{{$mainOwnBook['author']}}</x-ui.link-simple>
                </div>
                @if($mainOwnBook['annotation'])
                    <p class="line-clamp-5">{{$mainOwnBook['annotation']}}</p>
                @else
                    <p>Здесь скоро появится аннотация книги.</p>
                @endif
                <x-ui.link href="{{route('portal.own_book', $mainOwnBook['slug'])}}" class="w-fit">Подробнее</x-ui.link>
            </div>
        </div>
    @endif
    @if($ownBooks->count() > 0)
        <div class="w-full px-16 relative flex gap-4">
            <x-heroicon-o-chevron-left
                class="w-12 absolute left-0 top-1/2 -translate-y-1/2 transition duration-100 hover:scale-110 cursor-pointer"
                id="ownBooksSliderPrev"
            />
            <style>
                .swiper-slide a {
                    pointer-events: auto;
                }
            </style>
            <div class="swiper ownBooksSlider">
                <div class="swiper-wrapper">
                    @foreach($ownBooks as $ownBook)
                        <div class="p-2 swiper-slide !w-fit">
                            <x-ui.cards.card-own-book
                                x-on:click="Livewire.navigate('{{ route('portal.own_book', $ownBook->slug) }}')"
                                :ownbook="$ownBook" :cover3d="false"/>
                        </div>
                    @endforeach
                </div>
                <div class="swiper-pagination"></div>
            </div>
            <x-heroicon-o-chevron-right
                class="w-12 absolute right-0 top-1/2 -translate-y-1/2 transition duration-100 hover:scale-110 cursor-pointer"
                id="ownBooksSliderNext"
            />
        </div>
    @endif

    @push('scripts')
        <script type="module">
            var swiper = new Swiper(".ownBooksSlider", {
                slidesPerView: 'auto',
                spaceBetween: 30,
                navigation: {
                    nextEl: "#ownBooksSliderNext",
                    prevEl: "#ownBooksSliderPrev",
                },
                clickable: true,
                allowTouchMove: true,
            });
        </script>
    @endpush
</section>
