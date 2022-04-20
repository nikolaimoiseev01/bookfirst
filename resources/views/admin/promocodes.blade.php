@extends('layouts.admin_layout')
@section('title', 'Транзакции')
@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <h1 class="m-0">Наши промокоды</h1>
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
    <style>
        tr:hover {
            cursor: pointer;
        }
    </style>
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            @livewire('promocodes-admin-page')
        </div>
    </section>
    <!-- /.content -->
@endsection
