<div class="general_info_wrap part">
    {!! $page_style !!}
    <div class="line"></div>
    {!! $status_icon !!}
    <div class="container block_wrap">

        <div class=hero_wrap>
            <h2>Моя заявка</h2>
        </div>
        <div class="info_wrap">

            <div class="top_wrap">
                <div class="part_part">
                    <div class="name">
                        <h2>Логин:</h2>
                        <p>
                            <i> {{$ext_promotion['login']}}</i>
                        </p>
                    </div>

                    <div class="works_info_wrap">
                        <h2>Сайт:
                            <p>{{$ext_promotion['site']}}
                            </p>
                        </h2>
                    </div>

                    <div class="works_info_wrap">
                        <h2>Дней продвижения:
                            <p>{{$ext_promotion['days']}}
                            </p>
                        </h2>
                    </div>
                </div>

                <div class="part_part">
                    <div class="name">
                        <h2>Создана:</h2>
                        <p>
                            <i> {{Date::parse($ext_promotion['created_at'])->addHours(3)->format('j F H:i')}}</i>
                        </p>
                    </div>


                    <div class="works_info_wrap">
                        @if($ext_promotion['paid_at'])
                            <h2>Оплачена:
                                <p>{{Date::parse($ext_promotion['paid_at'])->addHours(3)->format('j F H:i')}}
                                </p>
                            </h2>
                        @else
                            <h2>Еще не оплачена
                            </h2>
                        @endif
                    </div>

                    <div class="works_info_wrap">
                        @if($ext_promotion['ext_promotion_status_id'] == 3)
                            <h2 style="width: max-content;">Начало продвижения:
                                <p>{{Date::parse($ext_promotion['updated_at'])->addHours(3)->format('j F H:i')}}
                                </p>
                            </h2>
                        @endif
                    </div>

                </div>

            </div>

        </div>

    </div>
</div>
