@extends('layouts.portal_layout')

@section('page-title')Соц. сеть@endsection

@section('page-style')
    <link rel="stylesheet" href="/css/home.css">
    <link rel="stylesheet" href="/css/social.css">
    <link rel="stylesheet" href="/css/books-example.css">
    <link rel="stylesheet" href="/plugins/slick/slick.css">
@endsection


@section('content')

    <img style="z-index: -1;" class="back-vector-right" src="/img/social/welcome_vector_right.svg">

    <div class="content">
        @include('layouts.parts.user_portal_header')






        <div style="margin-bottom: 30px; display:flex;">
            <div class="user_works_block">
                <div class="user_works_header">
                    <h3 style="margin: 0"> Произведения </h3>
                </div>
               <div class="user_works">
                   @livewire('work-feed',[
                   'works'  => $works,
                   'user_page_flag' =>  true
                   ])
                </div>
            </div>

            <div class="last_other_works_block">
                <h3 style="margin-bottom: 20px;">
                    Другие работы
                </h3>


                    @foreach($last_other_works as $last_work)
                        <div id="other_work_{{$loop->index}}" data-id="{{$last_work['id']}}" style="margin: 10px 0 25px 0;" class="other_work">
                            <div style="position: relative; height: 140px;">
                                <div class="read_main_hovered">
                                    <a style="padding: 3px 20px;" target="_blank"
                                       href="{{route('social.work_page', $last_work['id'])}}">Читать</a>
                                </div>
                                <img src="{{$last_work['picture_cropped'] ?? '/img/social/default_work_pic_cropped.png'}}" alt="">
                            </div>

                            <div class="other_work_icon_block">
                                <div class="other_work_icon_background">
                                                <span>@if($last_work->work_like) {{ $last_work->work_like->count('id') ?? 0}} @else
                                                        0 @endif</span>
                                    <i class="fa-regular fa-heart"> </i>
                                </div>
                                <div class="other_work_icon_background">
                                                <span>@if($last_work->work_comment) {{ $last_work->work_comment->count('id') ?? 0}} @else
                                                        0 @endif</span>
                                    <i class="fa-regular fa-comment"></i>
                                </div>
                            </div>
                            <div class="other_work_info">
                                <a href="{{route('social.user_page', $last_work['user_id'])}}" target="_blank" class="link_social">
                                    {{($last_work->user['nickname'])
                                        ? $last_work->user['nickname']
                                        :$last_work->user['name'] . ' ' . $last_work->user['surname']}}
                                </a>
                                <p>{{Str::limit(Str::ucfirst(Str::lower($last_work['title'])), 20, '...')}}</p>
                            </div>
                        </div>
                    @endforeach

            </div>
        </div>
    </div>

@endsection
