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
</div>
@endsection
