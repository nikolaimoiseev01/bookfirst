@extends('layouts.app')

@section('page-title')
    <div class="account-header">
        <h1>Новая заявка на продвижение</h1>
    </div>
@endsection

@section('page-tab-title')
    Создание заявки
@endsection

@section('content')
    @livewire('account.ext-promotion.application')
@endsection

@push('page-js')
@endpush
