@extends('layouts.admin_layout')
@section('title', 'Добавить сборник')
@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Добавить сборник</h1>
                </div><!-- /.col -->
            </div><!-- /.row -->
            @if (session('success'))
                <div class="alert alert-success" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h4><i class="icon fa fa-check"></i>{{ session('success') }}</h4>
                </div>
            @endif
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card card-primary">
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form action="{{ route('collection.store') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="card-body">
                                <div class="mb-4 form-row">
                                    <div class="mb-3 col-md-4">
                                        <div class="mb-2">
                                            Название
                                            <input type="text" name="title" class="form-control" id="exampleInputEmail1"
                                                   placeholder="Название сборника" required>
                                        </div>
                                        <div class="mb-2">
                                            Имя папки
                                            <input type="text" name="folder_name" class="form-control"
                                                   placeholder="Имя папки" required>
                                        </div>
                                        <div class="mb-2">
                                            Статус
                                            <select name="col_status_id" class="form-control">
                                                @foreach ($col_statuses as $col_status)
                                                    <option
                                                        value="{{ $col_status['id'] }}">{{ $col_status['col_status'] }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="mb-2"> Обложка 2d
                                            <input type="file" name="cover_2d"
                                                   class="d-none form-control custom-file-input" id="cover_2d"
                                                   aria-describedby="myInput">

                                            <label id="label_cover_2d" class="position-relative form-control custom-file-label"
                                                   for="cover_2d"></label>
                                        </div>
                                        <div class="mb-2"> Обложка 3d
                                            <input type="file" name="cover_3d"
                                                   class="d-none form-control custom-file-input" id="cover_3d"
                                                   aria-describedby="myInput">

                                            <label id="label_cover_3d"  class="position-relative form-control custom-file-label"
                                                   for="cover_3d"></label>
                                        </div>

                                    </div>

                                    <div style="width:100%;" class="mb-3 col-md-5">
                                        Описание сборника
                                        <textarea class="h-100 form-control" name="col_desc"
                                                  placeholder="Описание сборника"></textarea>
                                    </div>

                                    <div class="mb-3 col-md-3">
                                        <div class="mb-2 date">Конец приема заявок <input name="col_date1"
                                                                                          class="datepicker"
                                                                                          id="datepicker1"/></div>
                                        <div class="mb-2 date">Предварительные экземпляры <input name="col_date2"
                                                                                                 class="datepicker"
                                                                                                 id="datepicker2"/>
                                        </div>
                                        <div class="mb-2 date">Начало печати <input name="col_date3" class="datepicker"
                                                                                    id="datepicker3"/></div>
                                        <div class="mb-2 date">Печать до<input name="col_date4" class="datepicker"
                                                                               id="datepicker4"/></div>
                                    </div>
                                </div>

                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">Добавить</button>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- /.card-body -->
            </div>
        </div>
    </section>
    <!-- /.content -->
@endsection
