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
    <form action="{{route('work.update', $work->id)}}" method="post" enctype="multipart/form-data">
        @method('PUT')
        @csrf
        <div class="add-work-form">
            <input value="{{$work['title']}}" type="text" placeholder="Название" name="title" id="title">
            <textarea type="text" placeholder="Текст произведения" name="text" id="text">{{$work['text']}}</textarea>
        </div>
        <input style="display: none" value="{{$work['symbols']}}" type="number" name="symbols" id="symbols">
        {{--        <input type="number" min="1" name="symbols_to_rows" id="symbols_to_rows">--}}
        <input style="display: none" value="{{$work['rows']}}" type="number" name="rows" id="rows">
        <input style="display: none" value="{{$work['pages']}}" type="number" min="1" name="pages" id="pages">
        <button type="submit" class="button">Обновить</button>
    </form>
@endsection

@section('page-js')
    <script>

        symbols = 0;
        pages = 1;
        $('#poem-input').bind('input propertychange', function () {
            var symbol = $(this).val().split('');
            symbols = $(this).val().length;
            symbols_to_rows = 0;
            rows = 1;
            $.each(symbol, function(){

                if (this == '\n') {
                    rows++;
                    symbols_to_rows = 0;
                }
                else {
                    if (symbols_to_rows > 5) {
                        rows++; symbols_to_rows = 0;
                    } else {
                        symbols_to_rows++
                    }
                };

                $('#rows').val(rows);
                $('#symbols').val(symbols);
                pages = Math.ceil(rows / 33);
                $('#pages').val(pages);
                $('#symbols_to_rows').val(symbols_to_rows);
            });
        });
    </script>
@endsection
