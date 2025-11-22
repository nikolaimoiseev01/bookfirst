@extends('layouts.portal_layout')

@section('page-title') Выпущенные книги @endsection

@section('content')
    <div class="old_cols_page_wrap page_content_wrap">

        <div class="header_wrap">
            <h2>Выпущенные книги</h2>
        </div>

        <livewire:portal.own-books/>

    </div>

@endsection

