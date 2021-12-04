@extends('layouts.app')

@section('page-tab-title')
    Произведения
@endsection

@section('page-title')
    <div class="account-header">
        <h1 id="page_title">Мои произведения</h1>

        <span class="header-button-wrap tooltip" title="Одно произведение вручную">
        <a style="box-shadow: none;" href="{{route('work.create')}}" onclick="make_session()" class="fast-load button">Добавить</a>
        </span>

        <span class="tooltip" title="Несколько произведений сразу">
        <a style="box-shadow: none;" href="{{route('create_from_doc')}}" onclick="make_session()"
           class="fast-load button">Добавить файлом</a>
        </span>

        <div class="search-bar-wrap">
            <input required placeholder="Поиск..."
                   @if ($work_input_search <> 'no_search') value="{{$work_input_search}}" @else value=""
                   @endif id="work_input_search" name="work_input_search" type="text">

            <a id="work_input_search_link" href="">
                <svg width="15px" viewBox="0 0 612 612.01">
                    <g id="_4" data-name="4">
                        <path
                            d="M606.21,578.71l-158-155.48c41.38-45,66.8-104.41,66.8-169.84C515,113.44,399.7,0,257.49,0S0,113.44,0,253.39s115.27,253.4,257.48,253.4A259,259,0,0,0,419.56,450.2L578.18,606.3a20,20,0,0,0,28,0A19.29,19.29,0,0,0,606.21,578.71ZM257.49,467.8c-120.32,0-217.87-96-217.87-214.41S137.17,39,257.49,39s217.87,96,217.87,214.4S377.82,467.8,257.49,467.8Z"
                            transform="translate(-0.01 0)"/>
                    </g>
                </svg>
            </a>
            <script>
                $(function () {
                    $("#work_input_search").on('change', function (e) {
                        $("#work_input_search_link").attr("href", "/myaccount/work/search/" + $(this).val());
                    });
                });
            </script>
        </div>
        @if ($work_input_search <> 'no_search')
            <a style="margin-left: 20px; color: #ff6868;" href="{{route('work.index')}}" class="fast-load link">
                Очистить поиск
            </a>
        @endif
    </div>
@endsection
@section('content')
    @if($page_type == 'no_search')
        @livewire('my-works', ['page_type' => 'no_search', 'work_input_text' => ''])
    @else
        @livewire('my-works', ['page_type' => 'search', 'work_input_text' => $work_input_search])
    @endif
@endsection

@section('page-js')
    {{Session(['back_after_add' => \Livewire\str(Request::url())])}}



@endsection
