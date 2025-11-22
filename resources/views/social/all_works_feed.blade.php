@extends('layouts.portal_layout')

@section('page-title')Соц. сеть@endsection

@section('content')

    <div class="social_all_works_page_wrap page_content_wrap">


        <img style="z-index: -1;" class="back-vector-right" src="/img/social/welcome_vector_right.svg">

        <div class="page_title_wrap">
            <h3> Лента произведений </h3>
            <p>{{count($works)}} шт.</p>
        </div>

        <div class="user_works">
            @livewire('social.work-feed',[
            'works' => $works,
            'user_page_flag' => false
            ])
        </div>

    </div>

@endsection
