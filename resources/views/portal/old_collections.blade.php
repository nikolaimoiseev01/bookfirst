@extends('layouts.portal_layout')

@section('page-title') Выпущенные сборники @endsection

@section('content')
    <div class="old_cols_page_wrap page_content_wrap">

        <div class="header_wrap">
            <a href="{{route('actual_collections')}}">
                <h2>Актуальные</h2>
            </a>
            <h2>Выпущенные</h2>
        </div>

        <livewire:portal.old-collections/>


    </div>

@endsection

