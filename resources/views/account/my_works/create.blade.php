@extends('layouts.app')

@section('page-tab-title')
    Добавление произведения
@endsection

@section('page-title')
    <div class="account-header">
        <h1>Добавление произведения</h1>
    </div>
@endsection

@section('content')
    @livewire('account.work.work-form', ['form_type' => 'create', 'work_id' => null])
@endsection

@section('page-js')

@endsection

