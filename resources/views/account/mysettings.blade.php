@extends('layouts.app')

@section('page-tab-title')
    Мои настройки
@endsection

@section('page-title')
    <div class="account-header">
        <h1>Мои настройки</h1>
    </div>
@endsection
@section('content')
    <div class="settings">
        @livewire('my-settings')
    </div>
@endsection
