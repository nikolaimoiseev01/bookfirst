@extends('layouts.portal_layout')

@section('page-title')Соц. сеть@endsection

@section('page-style')
    <link rel="stylesheet" href="/css/home.css">
    <link rel="stylesheet" href="/css/social.css">
    <link rel="stylesheet" href="/css/books-example.css">
    <link rel="stylesheet" href="/plugins/slick/slick.css">
@endsection


@section('content')
    <div class="content">
        @include('layouts.parts.user_portal_header')

        <div class="work_block">
            <div>
                <h2>{{$work['title']}}  </h2>
            </div>

            <div>
                <p>{!! nl2br($work['text']) !!}</p>
                <div style="margin-top: 20px;">
                    <img style="max-width: 300px;" src="{{$work['picture']}}" alt="">
                </div>

            </div>
            {{App::setLocale('ru')}}
            <div>
                <p>
                    <b>Рубрика:</b>
                    <span style="">
                    @if ($work['work_type_id'] == 999)
                            {{Str::lower($work->work_topic['name'])}}
                        @elseif ($work['work_topic_id'] == 999)
                            {{Str::lower($work->work_type['name'])}}
                        @else
                            {{Str::lower($work->work_type['name'])}}/{{Str::lower($work->work_topic['name'])}}
                        @endif


                </span>
                </p>

                <br>

                <p>
                    <b>Опубликовано:</b>
                    <span
                        style=""> {{ Date::parse($work['created_at'])->format('j F Y') }}</span>
                </p>

                <br>

                <div style="display: flex;     align-items: center;">
                    <p style="margin-right: 10px;">
                        <b>Нравится:</b>
                        @livewire('like-button', ['work_id' => $work->id])
                    </p>
                </div>


            </div>
        </div>


        @livewire('work-comments', ['work_id' => $work['id']])
@endsection
