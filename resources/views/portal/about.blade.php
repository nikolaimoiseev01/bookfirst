@extends('layouts.portal_layout')


@section('page-style')
    <link rel="stylesheet" href="/plugins/slick/slick.css">
    <link rel="stylesheet" href="css/our_examples.css">
    <style>
        .rights {
            display: flex;
            width: 100%;
            border-top: 1px #ccc solid;
            margin-top: 20px;
            padding-top: 10px;
        }

        .rights-docs {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            margin-right: 20px;
        }

        @media screen and (max-width: 670px) {
            .rights {
                flex-direction: column;
                align-items: center;
            }

            .rights-docs {
                align-items: center;
                margin: 0 0 20px 0;
            }

            .rights-info {
                text-align: center;
            }
        }
    </style>

@endsection



@section('page-title') Наши сборники @endsection



@section('content')
    <div style="max-width: 1600px;" class="content">
        <h2 class="page-title">О нас</h2>


        <div style="flex-direction: column; align-items: flex-start;" class="normal-page-container container">
            <p>“Первая Книга” приветствует Вас на нашем сайте!
                Мы предоставляем возможность авторам воплощать их мечты в жизнь. Если Вы не представляете себя без пера
                в руке и желаете оставить след в истории литературы, если Вы не проживаете ни дня без новой иллюстрации,
                если Вы посвящаете жизнь науке и желаете опубликовать Ваши труды, то наша компания – к Вашим услугам!

                Мы организуем весь процесс публикации. Это может быть что угодно, начиная от тематических сборников
                стихотворений различных авторов, и заканчивая отдельными книгами одного автора, будь то собрание
                повестей, картин или личные мемуары. Мы берем на себя процесс верстки, проверки текста, составления
                содержания, а также регистрации книги, присвоения ей уникального номера ISBN, а также ее размещения на
                всемирных книжных интернет площадках (Amazon.com, Ozon.ru, Books.ru и т. д.).</p>

            <div class="rights">
                <div class="rights-docs">
                    <h2 style="margin: 0 0 10px 0; font-size: 25px;">Правовая информация:</h2>
                    <a href="" download class="link">Договор на участие в сборниках</a>
                    <a href="" download class="link">Договор на издание книги</a>
                </div>
                <div class="rights-info">
                    <h2 style="margin: 0 0 10px 0; font-size: 25px;">Юридическое лицо:</h2>
                    <p style="font-size: 20px;"><i>ОГРНИП 321100100001571<br>
                            ИНН 100126488117<br>
                            Е-mail: main@pervajakniga.ru<br>
                            Веб-сайт: pervajakniga.ru</i>
                    </p>
                </div>
            </div>
        </div>


    </div>

@endsection

@section('page-js')



@endsection
