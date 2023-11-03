<div class="collections_block_wrap">
    <x-input.search-bar
        model="search_input"
        :input="$search_input"></x-input.search-bar>
    <div wire:loading class="loader loading_process">
        <span class="button--loading"></span>
    </div>
    <div class="collections_wrap">

        @if($collections && count($collections) > 0)
            @foreach($collections as $collection)

                <div class="collection_wrap container">
                    @if($collection['cover_3d'])
                        <img data-effect="mfp-zoom-in" width="200px"
                             src="{{config('app.url') . '/' . $collection['cover_3d']}}"
                             alt="">
                    @endif
                    <div class="right_wrap">
                        <h3 class="title">{{$collection['title']}}</h3>
                        <div class="buttons_wrap">
                            <a @if ($collection['amazon_link'])
                               target="_blank" href="{{$collection['amazon_link']}}"
                               @endif
                               class="@if (!$collection['amazon_link']) no_amazon @endif button">
                                Купить на Amazon
                            </a>

                            <form action="{{ route('payment.create_buying_collection', $collection['id'])}}"
                                  method="POST"
                                  enctype="multipart/form-data">
                                @csrf
                                <button href="{{route('my_digital_sales')}}" id="btn-submit" type="submit"
                                        class="log_check pay-button button">
                                    Электронная версия <span>(100 руб.)</span>
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
