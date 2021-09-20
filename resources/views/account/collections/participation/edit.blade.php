@extends('layouts.app')

@section('page-style')
    <link rel="stylesheet" href="/css/create-participation.css">
@endsection

@section('page-tab-title')
    Редактирование заявки
@endsection

@section('page-title')
    <div class="account-header">
        <h1>Редактирование заявки в сборник {{$collection['title']}}</h1>
    </div>
@endsection

@section('content')
    @if (count($errors) > 0)
        <div class="alert alert-danger">
            <strong>Упс</strong> Что-то пошло не так:
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    @livewire('edit-participation', ['collection' => $collection, 'participation' => $participation])

@endsection

@section('page-js')
    <script src="/js/create-participation.js"></script>
    <script>
        $(".works-to-go").sortable({
            start: function (event, ui) {
                ui.item.toggleClass("start-anim");
            },
            placeholder: "to-drop",
            revert: true,
            stop: function (event, ui) {
                ui.item.toggleClass("stop-anim");
            }
        });
        $(".works-to-go").disableSelection();

    </script>
@endsection
