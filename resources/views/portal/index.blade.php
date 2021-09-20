@extends('layouts.portal_layout')

@section('page-title')Главная@endsection

@section('page-style')
    <link rel="stylesheet" href="/css/home.css">
    <link rel="stylesheet" href="/css/books-example.css">
    <link rel="stylesheet" href="/plugins/slick/slick.css">
@endsection

@section('content')
    <div id="modal_video_hero" class="modal">
        <div class="modal-wrap">
            <iframe id="video_hero_iframe" width="740" height="420" src="https://www.youtube.com/embed/q9YOJS_6FMg" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
        </div>
    </div>
    <div class="welcome-block">


        <div class="hero-wrap">
            <img class="back-vector-left" src="/img/Back vector left.svg">
            <div class="hero">
                <h1>Ваш шаг в мир литературы</h1>
                <span><i>Что разум человека может постигнуть и во что он может поверить, того он способен достичь</i></span>
                <div class="call-buttons">

                    <div class="cta-container">
                        <a href="/#actual-block" class="cta-btn">Опубликовать</a>
                    </div>
                    <a data-modal="modal_video_hero" class="modal-from how-it-works"><i style="margin-right: 8px;" class="far fa-play-circle"></i>Как это
                        работает</a>

                </div>
            </div>
        </div>
        <div class="hero-woman-wrap">
            <img class="woman" src="/img/woman.svg">
            <img class="back-vector-right" src="/img/Back vector right.svg">
        </div>
    </div>

    <div class="example-block">

        <div class="ex-left">
            <div class="covers-ex-wrap">
                <img id="0" class="cover active" src="/img/main_ex_0.png" alt="">
                <img id="1" class="cover" src="/img/main_ex_1.png" alt="">
                <img id="2" class="cover" src="/img/main_ex_2.png" alt="">
            </div>

            {{--            <div class="book-wrap">--}}
            {{--                <div class="book">--}}
            {{--                    <div class="book-pages">--}}
            {{--                        <div>--}}
            {{--                            <img id="0" src="/img/Ex_1.png" class="book-cover cover active">--}}
            {{--                            <img id="1" src="/img/Ex_2.png" class="book-cover cover">--}}
            {{--                            <img id="2" src="/img/Ex_3.png" class="book-cover cover">--}}
            {{--                        </div>--}}
            {{--                        <div></div>--}}
            {{--                        <div></div>--}}
            {{--                        <div></div>--}}
            {{--                        <div></div>--}}
            {{--                        <div></div>--}}
            {{--                    </div>--}}
            {{--                    <div class="book-edge"></div>--}}
            {{--                </div>--}}
            {{--                <img style="z-index: -1" class="book-ex-back" src="/img/Ellipse 159.svg" alt="">--}}
            {{--            </div>--}}

            <div class="change_ex_buttons">
                <a class="change_ex" id="prev"><img src="/img/prev.svg" alt=""></a>
                <div class="line-in">
                    <div class="line-out"></div>
                </div>
                <a class="change_ex" id="next"> <img src="/img/next.svg" alt=""></a>
            </div>

            <img class="woman-2-circle" src="/img/actual-back-circle 2.svg" alt="">
        </div>

        <div class="ex-right">
            <div class="project-wrap">
                <div id="ex_0" class="project active_text">
                    <h2 class="ex_name">Современный Дух Поэзии</h2>
                    <p class="ex_desc letters">Взгляды поэтов неоднозначны, многослойны и объёмны, они олицетворяют
                        настоящую жизнь.
                        Более 30-ти изданных выпусков со стихотворения делаю что-то вообще вауч (придумать)</p>
                </div>
                <div id="ex_1" class="project">
                    <h2 class="ex_name">Сокровенные Мысли</h2>
                    <p class="ex_desc letters">Мысли – это неотъемлемая часть нашей жизни, мысль руководит нашим
                        сознанием.
                        В сборнике «Сокровенные мысли» мы собираем авторов,
                        прозы которых заставят каждого из Вас глубоко окунуться в мир непостижимого, которым мы
                        окружены.</p>
                </div>
                <div id="ex_2" class="project">
                    <h2 class="ex_name">Книга Фанфиков</h2>
                    <p class="ex_desc letters">Сборник современных авторов разных направлений со всего мира. Еще
                        какое-то подольше описание. Не знаю, что написаь здесь.</p>
                </div>
            </div>
            <div class="ex_buttons">
                <a style="font-size: 24px;" target="_blank" href="https://www.amazon.com/s?i=stripbooks&rh=p_27%3A%26%231053%3B%26%231048%3B+%26%231055%3B%26%231077%3B%26%231088%3B%26%231074%3B%26%231072%3B%26%231103%3B+%26%231050%3B%26%231085%3B%26%231080%3B%26%231075%3B%26%231072%3B&s=relevancerank&text=%26%231053%3B%26%231048%3B+%26%231055%3B%26%231077%3B%26%231088%3B%26%231074%3B%26%231072%3B%26%231103%3B+%26%231050%3B%26%231085%3B%26%231080%3B%26%231075%3B%26%231072%3B&ref=dp_byline_sr_book_1" class="button">Купить на Amazon</a>
                <a style="font-size: 24px;" target="_blank" href="/collections" class="button">Все наши работы</a>
            </div>
        </div>
    </div>

    <div class="history-block">
        <img src="/img/woman2.svg" alt="">
        <div class="history">
            <h2 class="title">За три года работы у нас:</h2>
            <div class="container">
                <div class="history-info">
                    <span class="history-number"><span class="history-number" id="counter1">0</span>+</span>
                    <span class="number-desc">Изданных сборников</span>
                </div>
                <div class="history-info">
                    <span class="history-number"><span class="history-number" id="counter2">0</span>+</span>
                    <span class="number-desc">Авторов</span>
                </div>
                <div class="history-info">
                    <span class="history-number"><span class="history-number" id="counter3">0</span>+</span>
                    <span class="number-desc">Иностранных участников</span>
                </div>
            </div>
        </div>
        <img class="history-back-circle" src="/img/actual-back-circle.svg" alt="">
    </div>

    <div style="margin-top: -85px; padding-top: 85px;" id="actual-block" class="actual-block">
        <div class="actual-title">
            <img src="/img/Ellipse 96.svg" alt="">
            <h2>Идет прием заявок</h2>
        </div>

        @foreach($collections as $collection)
            <div class="container">
                <div class="label-wrap">
                    <div class="label"><div>Заявки до:</div><div>{{$collection['col_date1']}}</div></div>
                </div>
                <div class="cover-wrap">
                    <img src="{{$collection['cover_3d']}}" alt="">
                </div>
                    <div class="info-wrap">
                        <h3> {{$collection['title']}}</h3>
                        <p> {{$collection['col_desc']}}</p>
                    </div>
                    <div class="buttons-wrap">
                        <a href="{{route('collection_page',$collection['id'])}}" class="button">Подробнее</a>
                        <a href="{{route('participation_create',$collection['id'])}}" class="log_check button">Принять
                            участие</a>
                    </div>
            </div>
        @endforeach

        <div class="container">
            <div class="cover-wrap">
                <img src="/img/Own_book_example_cover.png" alt="">
            </div>
            <div class="info-wrap">
                <h3> Ваша собственная книга</h3>
                <p> Мы также предлагаем издать Вашу собственную книгу.
                    Мы возьмем на себя весь процесс, начиная от верстки, проверки текста,
                    и заканчивая регистрацией книги, присвоения ей уникального номера ISBN,
                    а также ее размещение на всемирных книжных интернет площадках (Amazon.com, Books.ru и т. д.).
                </p>
            </div>
            <div class="buttons-wrap">
                <a href="{{route('own_book_page')}}" class="button">Подробнее</a>
                <a href="/" class="log_check button">Подать заявку</a>
            </div>
        </div>
    </div>

    <div class="own-examples-block">
        <div class="own-header">
            <h2>Книги наших авторов</h2>
            <a href="/own_books" target="_blank">Смотреть все</a>
        </div>
        <div class="own-slider">
            @foreach($own_books as $own_book)
{{--                <a href="/own_books/#own_book_{{$own_book['id']}}">--}}
                <div onclick="{window.open('/own_books/#own_book_{{$own_book['id']}}', '_blank');};return false;" class="container">
                    <div class="image-wraper">
                        <img src="{{$own_book['cover_3d']}}" alt="">
                    </div>
                    <h3>{{$own_book['author']}}</h3>
                    <p>{{$own_book['title']}}</p>
                </div>
{{--                </a>--}}
            @endforeach
        </div>
    </div>

    <div style="margin-top: -85px; padding-top: 85px;" id="reviews-block" class="reviews-block">
        <div class="own-header">
            <h2>Отзывы</h2>
            <a href="https://vk.com/topic-122176261_35858257" target="_blank">Больше отзывов</a>
        </div>
        <div class="reviews-wrap">
            <div class="review">
                <div class="container">
                    <p>
                        Дорогая Первая Книга! Очень Вам благодарна. Приятное впечатление, общение с Вами, добросовестное отношение к своему делу, оставило незабываемое впечатление! Вышел, долгожданный, сборник Современный Дух Поэзии. Выпуск 33, с моими стихами, и стихами других авторов. Хочу выразить огромную благодарность издательству "Первая Книга", за такую чудесную возможность печататься в сборниках начинающим писателям и не только! Ваше отношение к своей работе восхищает, корректностью, искренней доброжелательностью, оперативностью!
                    </p>
                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 26.4 16.08"><defs><style>.cls-1{isolation:isolate;}.cls-2{opacity:0.06;mix-blend-mode:color-burn;}.cls-3{fill:#fff;}</style></defs><g class="cls-1"><g id="Слой_1" data-name="Слой 1"><image class="cls-2" width="110" height="67" transform="scale(0.24)" xlink:href="data:image/png;base64,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"/><path class="cls-3" d="M0,10H20l-9.56,9.55a.62.62,0,0,1-.5.23.63.63,0,0,1-.43-.21Z" transform="translate(3.24 -7.8)"/></g></g></svg>
                </div>
                <div class="review-author-wrap">
                    <img src="/img/test.png" alt="" class="avatar">
                    <span>Анжелика Пархомцева</span>
                </div>
            </div>

            <div class="review">
                <div class="container">
                    <p>
                        Большое спасибо издательству "Первая книга" за выпуск поэтического сборника "Современный дух поэзии 24" . Такие проекты дают стимул для дальнейшего развития и дарят вдохновение! Книжечки в количестве 10 штук получила в полном обьеме, оформление очень красивое , упаковано было качественно , при пересылке ничего не повредилось , плотная белая бумага, необычная обложка и эффектный дизайн . Всех участников оповестили по электронной почте о готовности отправки бандеролек и прислали списком треки для отслеживания.
                    </p>
                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 26.4 16.08"><defs><style>.cls-1{isolation:isolate;}.cls-2{opacity:0.06;mix-blend-mode:color-burn;}.cls-3{fill:#fff;}</style></defs><g class="cls-1"><g id="Слой_1" data-name="Слой 1"><image class="cls-2" width="110" height="67" transform="scale(0.24)" xlink:href="data:image/png;base64,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"/><path class="cls-3" d="M0,10H20l-9.56,9.55a.62.62,0,0,1-.5.23.63.63,0,0,1-.43-.21Z" transform="translate(3.24 -7.8)"/></g></g></svg>
                </div>
                <div class="review-author-wrap">
                    <img src="/img/test.png" alt="" class="avatar">
                    <span>Ольга Раевская</span>
                </div>
            </div>

            <div class="review">
                <div class="container">
                    <p>
                        Дорогая "Первая Книга" ! Огромное СПАСИБО, что помогла преодолеть в себе неуверенность и понять, что если читают и ждут новых моих " литературных творений", - значит ЭТО КОМУ-ТО НУЖНО! Когда твоя страничка в инете, и есть читатели и отзывы на произведения, -это одно...Но когда держишь сборники, в которых есть и твой Труд,- и ты этим можешь поделиться со всем МИРОМ,- это Радость величайшая! Я так благодарна людям, работающим над изданием этих сборников. Есть еще и уникальная возможность познакомиться и с авторами,- теперь уже "Собратьев по перу".
                    </p>
                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 26.4 16.08"><defs><style>.cls-1{isolation:isolate;}.cls-2{opacity:0.06;mix-blend-mode:color-burn;}.cls-3{fill:#fff;}</style></defs><g class="cls-1"><g id="Слой_1" data-name="Слой 1"><image class="cls-2" width="110" height="67" transform="scale(0.24)" xlink:href="data:image/png;base64,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"/><path class="cls-3" d="M0,10H20l-9.56,9.55a.62.62,0,0,1-.5.23.63.63,0,0,1-.43-.21Z" transform="translate(3.24 -7.8)"/></g></g></svg>
                </div>
                <div class="review-author-wrap">
                    <img src="/img/test.png" alt="" class="avatar">
                    <span>Венера Коновалова</span>
                </div>
            </div>
        </div>
    </div>



@endsection

@section('page-js')

    <script src="/js/anime.min.js"></script>
    <script src="/js/books-example.js"></script>
    <script src="/plugins/slick/slick.min.js"></script>
    <script>
        $('.own-slider').slick({
            infinite: true,
            slidesToShow: 4,
            slidesToScroll: 1,
            arrows: true,
            responsive: [
                {
                    breakpoint: 1300,
                    settings: {
                        slidesToShow: 3,
                    }
                },
                {
                    breakpoint: 900,
                    settings: {
                        slidesToShow: 2,
                    }
                },
                {
                    breakpoint: 600,
                    settings: {
                        slidesToShow: 1,
                    }
                }],
        });
    </script>
    <script>
        function animateValue(obj, start, end, duration) {
            let startTimestamp = null;
            const step = (timestamp) => {
                if (!startTimestamp) startTimestamp = timestamp;
                const progress = Math.min((timestamp - startTimestamp) / duration, 1);
                obj.innerHTML = Math.floor(progress * (end - start) + start);
                if (progress < 1) {
                    window.requestAnimationFrame(step);
                }
            };
            window.requestAnimationFrame(step);
        }

        $(window).on("scroll", function () {
            if (parseInt($('#counter1').text()) < 1 && $(this).scrollTop() > $('.history .container').offset().top - $(window).height()) {
                animateValue(document.getElementById("counter1"), 0, 70, 3000);
                {
                    animateValue(document.getElementById("counter2"), 0, 1100, 3000);
                }
                ;
                {
                    animateValue(document.getElementById("counter3"), 0, 150, 3000);
                }
                ;
            }
            ;

        })


        // $('#mf-modal_video_hero').on('click', function() {
        //     $("#video_hero_iframe").attr('src','https://www.youtube.com/embed/q9YOJS_6FMg');
        // });


    </script>


@endsection
