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

        <div style="margin-bottom: 30px; display:flex;">
            <div style="margin-top: 30px;" class="user_works_block">
                <div class="user_works_header">
                    <h3 style="margin: 0"> Лента произведений </h3>
                </div>
                <div class="user_works">
                    @livewire('work-feed',[
                    'works' => $works,
                    'user_page_flag' =>  false
                    ])
                </div>
            </div>


        </div>
    </div>

@endsection
