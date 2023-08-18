@extends('layouts.portal_layout')

@section('page-title')Соц. сеть@endsection

@section('content')

    <div class="social_user_page_wrap page_content_wrap">


        <img style="z-index: -1;" class="back-vector-right" src="/img/social/welcome_vector_right.svg">

        <x-social.user-page-header :user="$user"></x-social.user-page-header>

        <div class="works_block_wrap">
            <div class="user_works_wrap">
                <h3> Произведения </h3>
                @livewire('social.work-feed',[
                'works' => $works,
                'user_page_flag' => true
                ])
            </div>

            <div class="other_works_wrap">
                <h3> Другие работы </h3>

                @foreach($last_other_works as $last_work)
                    <x-social.work-card :work="$last_work" flgbigwork="false"/>
                @endforeach

            </div>
        </div>

    </div>

@endsection
