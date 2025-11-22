<section id="ownBooks" class="content mb-32">
    <div class="flex w-full justify-between items-center mb-8">
        <h2>Книги наших авторов</h2>
        <x-ui.link-simple class="text-3xl" href="{{route('portal.own_books.released')}}">Все книги</x-ui.link-simple>
    </div>
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
                        <x-ui.cards.card-own-book x-on:click="Livewire.navigate('{{ route('portal.own_book', $ownBook->slug) }}')" :ownbook="$ownBook"  :cover3d="false"/>
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
