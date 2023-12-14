<div class="own_book_card_small_wrap container">
    <a href="{{route('own_book_user_page', $own_book['id'])}}">
        <img src="/{{$own_book['cover_2d']}}" alt="">
        <div class="info_wrap">
            <p class="name">
                <b>
                    {{Str::limit(Str::ucfirst(Str::lower($own_book['title'])), 20, '...')}}
                </b>
            </p>
            <a class="social link title">{{$own_book['author']}}</a>
            {{--        <a href="/own_books?search_input={{$own_book['title']}}"--}}
            {{--           target="_blank"--}}
            {{--           class="button social">Подробнее</a>--}}
        </div>
    </a>
</div>
