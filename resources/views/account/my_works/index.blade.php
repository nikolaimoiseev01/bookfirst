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
        <a style="box-shadow: none;" href="{{route('create_from_doc')}}" onclick="make_session()" class="fast-load button">Добавить файлом</a>
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
    @if ($work_input_search <> 'no_search' & count($works) == 0)
        <p>По запросу <i>"{{$work_input_search}}"</i> произведений не найдено</p>
    @endif
    <div class="element-wrap">
        {{App::setLocale('ru')}}
        @foreach($works as $work)
            <div class="container">
                <div class="el-desc">
                    <span>{{Str::limit($work['title'], 30)}}</span>
                    <p>Опубликовано: {{ Date::parse($work['created_at'])->format('j F') }}</p>
                </div>

                <div class="el-button-wrap">
                    <a href="{{route('work.edit', $work->id)}}">
                         <span class="tooltip" title="Редактировать">
                           <svg id="Слой_1" data-name="Слой 1" xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 401 398.98">
                            <path
                                d="M370.11,251.91a10,10,0,0,0-10,10v88.68a30,30,0,0,1-30,30H49.93a30,30,0,0,1-30-30V90.32a30,30,0,0,1,30-30h88.68a10,10,0,1,0,0-20H49.93A50,50,0,0,0,0,90.32V350.57A50,50,0,0,0,49.93,400.5H330.16a50,50,0,0,0,49.93-49.93V261.89A10,10,0,0,0,370.11,251.91Z"
                                transform="translate(0 -1.52)"/>
                            <path
                                d="M376.14,14.68a45,45,0,0,0-63.56,0L134.41,192.86a10,10,0,0,0-2.57,4.39l-23.43,84.59a10,10,0,0,0,12.29,12.3l84.59-23.44a10,10,0,0,0,4.4-2.56L387.86,90a45,45,0,0,0,0-63.56Zm-220,184.67L302,53.52l47,47L203.19,246.38Zm-9.4,18.85,37.58,37.58-52,14.39Zm227-142.36-10.6,10.59-47-47,10.6-10.59a25,25,0,0,1,35.3,0l11.73,11.71A25,25,0,0,1,373.74,75.84Z"
                                transform="translate(0 -1.52)"/>
                        </svg>
                       </span>

                    </a>
                </div>
            </div>
        @endforeach


    </div>
    {{ $works->links() }}

@endsection

@section('page-js')
    {{Session(['back_after_add' => \Livewire\str(Request::url())])}}

@endsection
