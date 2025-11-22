@extends('layouts.app')

@section('page-tab-title')
    Редактирование произведения
@endsection

@section('page-title')
    <div class="account-header">
        <h1>Редактирование произведения</h1>
    </div>
@endsection

@section('content')
    @livewire('account.work.work-form', ['form_type' => 'edit', 'work_id' => $work->id])
@endsection


