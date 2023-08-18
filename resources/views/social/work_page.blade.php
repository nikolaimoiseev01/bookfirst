@extends('layouts.portal_layout')

@section('page-title')Соц. сеть@endsection

@section('page-style')
    <link rel="stylesheet" href="/css/home.css">
    <link rel="stylesheet" href="/css/social.css">
    <link rel="stylesheet" href="/css/books-example.css">
    <link rel="stylesheet" href="/plugins/slick/slick.css">
@endsection


@section('content')
    <div class="page_content_wrap social_work_page_wrap">

        <x-social.user-page-header :user="$user"></x-social.user-page-header>

        <div class="work_wrap">
            <h2 class="work_name">{{$work['title']}}  </h2>
            <p>{!! nl2br($work['text']) !!}</p>
            <div>
                <img style="max-width: 300px;" src="{{$work['picture']}}" alt="">
            </div>
            <div class="info_wrap">
                <p>
                    <b>Рубрика:</b>
                    @if ($work['work_type_id'] == 999)
                        {{Str::lower($work->work_topic['name'])}}
                    @elseif ($work['work_topic_id'] == 999)
                        {{Str::lower($work->work_type['name'])}}
                    @else
                        {{Str::lower($work->work_type['name'] ?? 'не определено')}}/{{Str::lower($work->work_topic['name'] ?? 'не определено')}}
                    @endif
                </p>

                <p><b>Опубликовано:</b>{{ Date::parse($work['created_at'])->format('j F Y') }} </p>

                <div class="like_wrap"><p><b>Нравится:</b></p>
                    @livewire('social.like-button', ['work_id' => $work->id])
                </div>

            </div>
        </div>


        @livewire('social.work-comments', ['work_id' => $work['id']])
@endsection
