@extends('layouts.app')

@section('page-style')

@endsection

@section('page-tab-title')
    Заявка на издание
@endsection


@section('page-title')
    <div class="account-header">
        <h1>Заявка на издание собственной книги</h1>
    </div>
@endsection

@section('content')
@livewire('create-own-book')
@endsection

@section('paje-js')

@endsection
