<div class="own_books_block_wrap">
    <x-input.search-bar
        model="search_input"
        :input="$search_input"></x-input.search-bar>
    <div wire:loading class="loader loading_process">
        <span class="button--loading"></span>
    </div>
    <div class="own_books_wrap">
        @if($own_books && count($own_books) > 0)
            @foreach($own_books as $own_book)

                <div class="own_book_wrap container">
                    @if($own_book['cover_3d'])
                        <img data-effect="mfp-zoom-in" width="200px"
                             src="/{{$own_book['cover_3d']}}"
                             alt="">
                    @endif
                    <div class="right_wrap">
                        <h3 class="title">{{$own_book['title']}}</h3>
                        <p>{{$own_book['own_book_desc']}}</p>
                        <div class="buttons_wrap">
                            <a @if ($own_book['amazon_link'])
                               target="_blank" href="{{$own_book['amazon_link']}}"
                               @endif
                               class="@if (!$own_book['amazon_link']) no_amazon @endif button">
                                Купить на Amazon
                            </a>

                            <form action="{{ route('payment.create_buying_own_book', $own_book['id'])}}"
                                  method="POST"
                                  enctype="multipart/form-data">
                                @csrf
                                <button href="{{route('my_digital_sales')}}" id="btn-submit" type="submit"
                                        class="log_check pay-button button">
                                    Электронная версия (100 руб.)
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
            @if($loaded_cnt < $total_cnt)
                <div class="load_more_wrap">
                    <p>Загружено {{$loaded_cnt}} из {{$total_cnt}}</p>
                    <a wire:click.prevent="load_more()" class="link show_preloader_on_click">
                        Еще
                    </a>
                </div>

            @endif




        @else
            <h2 class="no-access">К сожалению, по запросу '{{$search_input}}' ничего не нашлось</h2>
        @endif
    </div>

</div>
