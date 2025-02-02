<div class="general_info_wrap part">
    {!! $page_style !!}
    <div class="line"></div>
    {!! $status_icon !!}
    <div class="container block_wrap">

        <div class=hero_wrap>
            <h2>Моя заявка</h2>
            @if($participation->collection['col_status_id'] < 3)
                <a href="{{route('participation_edit', [
                     'participation_id'=>$participation['id'],
                     'collection_id' => $participation['collection_id']
                     ])
                 }}" class="button">Редактировать</a>
            @endif
        </div>
        <div x-data="{ det_work_show: false }" class="info_wrap">

            <div class="top_wrap">
                <div class="part_part">
                    <div class="name">
                        <h2>Имя в сборнике:</h2>
                        <p>
                            <i> {{prefer_name($participation['name'], $participation['surname'], $participation['nickname'])}}</i>
                        </p>
                    </div>

                    <div class="works_info_wrap">
                        <h2>Произведений:
                            <p>{{$participation['works_number']}}
                                <a x-show="!det_work_show" @click="det_work_show = true" class="link">развернуть</a>
                            </p>
                        </h2>
                        <p>Строчек: <i>{{$participation['rows']}}</i></p>
                        <p>Страниц: <i>{{$participation['pages']}}</i></p>
                    </div>
                    @if($participation['check_price'] > 0)
                        <h2>Требуется редактура</h2>
                    @endif
                </div>

                <div class="print_part">
                    @if($participation->printorder ?? null)
                        <div class="name">
                            <h2>Печатных экземпляров:</h2>
                            <p>{{$participation->printorder['books_needed']}}</p>
                        </div>
                        <div class="div">

                        </div>
                        <div class="name">
                            <p><span class="h2">Адрес: </span>{{print_address($participation->printorder)}}</p>
                        </div>
                    @else
                        <h2>Печатные эезкемпляры не требуются.</h2>
                    @endif
                </div>
            </div>

            <div style="display: none;"
                 class="detailed_work_wrap">
                <h2>Произведения в заявке
                    <a x-show="det_work_show" @click="det_work_show = false" class="link">свернуть</a>
                </h2>
                <div class="works_wrap">
                    @foreach($participation->participation_work as $work)
                        @if($work->work ?? null)
                            <div class="work_wrap container">
                                <a class="link work_link" target="_blank"
                                   href="{{route('social.work_page', $work['work_id'])}}">{{$work->work['title']}}</a>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>

        @push('page-js')
            <script>
                $('.works_info_wrap a, .detailed_work_wrap a').not('.work_link').on('click', function () {
                    $('.detailed_work_wrap').slideToggle()
                })
            </script>
        @endpush
    </div>
</div>
