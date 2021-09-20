@extends('layouts.app')

@section('page-tab-title')
    Добавление произведения
@endsection

@section('page-title')
    <div class="account-header">
        <h1>Добавление произведений из файла</h1>
    </div>
@endsection

@section('content')
    @livewire('create-work-from-doc')
@endsection

@section('page-js')

@endsection

