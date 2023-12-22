<div class="own_book_card_small_wrap container">
    @if($own_book['own_book_status_id'] == 9)
        <a href="{{route('own_book_user_page', $own_book['id'])}}">
            @endif
            <img src="/{{$own_book['cover_2d'] ?? 'img/no_cover_2d.png'}}" alt="">
            @if($own_book['cover_2d'])
                @if($own_book['own_book_status_id'] < 9)
                    <span class="step in_process">В процессе</span>
                @elseif($own_book['own_book_status_id'] == 9)
                    <span class="step done">Издано</span>
                @endif
            @endif
            <div class="info_wrap">
                <p class="name">
                    <b>
                        {{Str::limit(Str::ucfirst(Str::lower($own_book['title'])), 20, '...')}}
                    </b>
                </p>
                <a class="social link title">{{$own_book['author']}}</a>
            </div>
            @if($own_book['own_book_status_id'] == 9)
        </a>
    @endif
</div>
