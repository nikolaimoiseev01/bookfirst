@extends('layouts.portal_layout')

@section('page-title')
    Соц. сеть
@endsection

@section('content')

    <div class="social_user_page_wrap page_content_wrap">


        <img style="z-index: -1;" class="back-vector-right" src="/img/social/welcome_vector_right.svg">

        <x-social.user-page-header :user="$user"></x-social.user-page-header>


        <div x-data="{ block: 'works' }" class="works_books_block_wrap">

            <div class="works_books_block">

                <div class="header">
                    <h3 :class="block=='works' ? 'active' : 'inactive'" @click="block='works'"> Произведения </h3>
{{--                    <h3 :class="block=='books' ? 'active' : 'inactive'" @click="block='books'"> Собственные книги </h3>--}}
                </div>


                <div x-show="block=='books'" class="books_block_wrap">
                    @foreach($own_books as $own_book)
                        <x-own-book-card-small :ownbook="$own_book"></x-own-book-card-small>
                    @endforeach
                </div>

                <div x-show="block=='works'" class="works_block_wrap">
                    @livewire('social.work-feed',[
                    'works' => $works,
                    'user_page_flag' => true
                    ])
                </div>

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
