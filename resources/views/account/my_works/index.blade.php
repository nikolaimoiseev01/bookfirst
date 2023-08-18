@extends('layouts.app')

@section('page-tab-title')
    Произведения
@endsection

@section('content')
    @livewire('account.my-works')
@endsection



@push('page-js')
    {{Session(['back_after_add' => \Livewire\str(Request::url())])}}
@endpush
