<div>
    <style>
        .table td {
            vertical-align: middle;
            text-align: center;

        }
    </style>
    <table id="print_orders_table" class="table table-bordered table-hover">
        <thead>
        <tr>
            <th>Автор</th>
            <th>ФИО адресата</th>
            <th>Адрес</th>
            <th>Телефон</th>
            <th>Кол-во</th>
            <th>Трек-номер</th>
        </tr>
        </thead>
        <tbody>
        @foreach($participations as $participation)

            @if ($participation->printorder['books_needed'] ?? 0 > 0)
                <tr>
                    <td style="width: 10%; text-align: center;">
                        {{$participation['name']}} {{$participation['surname']}}
                    </td>
                    <td style="width: 10%;  text-align: center;">
                        {{$participation->printorder['send_to_name']}}
                    </td>
                    <td style="width: 10%;  text-align: center;">
                        {{$participation->printorder['send_to_address']}}
                    </td>
                    <td>
                        {{$participation->printorder['send_to_tel']}}
                    </td>
                    <td>
                        {{$participation->printorder['books_needed']}}
                    </td>
                    <td>
                        <div>
                            @if(!!$participation->printorder['track_number'])
                                <div class="d-flex align-items-center justify-content-center" id="input_closed_wrap">
                                    <a target="_blank" style="
                                    @if ($show_input === 1)
                                        display:none;
                                    @else
                                        display:inline;
                                    @endif
                                        " id="track_{{$participation->printorder['id']}}" class="link-dark"
                                       href="https://www.pochta.ru/tracking#{{$participation->printorder['track_number']}}">
                                        {{$participation->printorder['track_number']}}
                                    </a>
                                    <i wire:ignore style="font-size: 20px; " id="{{$participation->printorder['id']}}"
                                       class="show_input p-0 ml-1 far fa-edit"></i>
                                </div>

                                <div wire:ignore class="d-none align-items-center" id="input_shown_wrap_{{$participation->printorder['id']}}">
                                    <input wire:model="track_number.{{$participation->printorder['id']}}"
                                           class="form-control"
                                           style="-webkit-appearance: none !important;" type="text">
                                    <a id="save_btn_{{$participation->printorder['id']}}" class="save_btn btn p-0 ml-1"
                                       wire:click.prevent="save_track_number({{$participation->printorder['id']}})">
                                        <i style="font-size: 24px;" class="far fa-save"></i>
                                    </a>
                                </div>
                            @else
                                <div class="d-flex align-items-center">
                                    <input wire:model="track_number.{{$participation->printorder['id']}}"
                                           class="form-control"
                                           style="-webkit-appearance: none !important;" type="text">
                                    <a class="btn p-0 ml-1"
                                       wire:click.prevent="save_track_number({{$participation->printorder['id']}})"><i
                                            style="font-size: 28px;" class="far fa-save"></i></a>

                            @endif
                                    {{--                        <i style="--}}
                                    {{--                        @if ($show_input === 1)--}}
                                    {{--                            display:none;--}}
                                    {{--                        @else--}}
                                    {{--                            display:inline;--}}
                                    {{--                        @endif--}}
                                    {{--                            font-size: 20px; " id="{{$participation->printorder['id']}}" wire:click.prevent="show_1()"--}}
                                    {{--                           class="btn p-0 ml-1 far fa-edit"></i>--}}
                                    {{--                            </div>--}}
                                    {{--                        <div style="--}}
                                    {{--                        @if ($show_input === 1)--}}
                                    {{--                            display:inline;--}}
                                    {{--                        @else--}}
                                    {{--                            display:none !important;--}}
                                    {{--                        @endif--}}
                                    {{--                            " id="inputs_{{$participation->printorder['id']}}" class="d-flex align-items-center">--}}
                                    {{--                            <input wire:model="track_number.{{$participation->printorder['id']}}" class="form-control"--}}
                                    {{--                                   value="{{$participation->printorder['track_number']}}"--}}
                                    {{--                                   style="-webkit-appearance: none !important;" type="text">--}}
                                    {{--                            <a class="btn p-0 ml-1"--}}
                                    {{--                               wire:click.prevent="save_track_number({{$participation->printorder['id']}})"><i--}}
                                    {{--                                    style="font-size: 28px;" class="far fa-save"></i></a>--}}
                                    {{--                        </div>--}}
                                </div>
                    </td>
                </tr>
            @endif
        @endforeach
        </tbody>
    </table>
    @section('page-js')
        <script>
            $('.show_input').on('click', function () {
                id = $(this).attr('id');
                $(this).toggleClass('far');
                $(this).toggleClass('fas');
                $(this).toggleClass('fa-edit');
                $(this).toggleClass('fa-times');

                $('#input_shown_wrap_' + id).toggleClass('d-none');
                $('#input_shown_wrap_' + id).toggleClass('d-flex');
            })

            $('.save_btn').on('click', function () {
                id = $(this).attr('id').substring(9,15);

                $('#' + id).toggleClass('far');
                $('#' + id).toggleClass('fas');
                $('#' + id).toggleClass('fa-edit');
                $('#' + id).toggleClass('fa-times');

                $('#input_shown_wrap_' + id).toggleClass('d-none');
                $('#input_shown_wrap_' + id).toggleClass('d-flex');
            })


        </script>

        {{--            <script>--}}
        {{--                $(document).ready(function () {--}}
        {{--                    $("#print_orders_input").on("keyup", function () {--}}
        {{--                        var value = $(this).val().toLowerCase();--}}
        {{--                        $("#print_orders_table tbody tr").filter(function () {--}}
        {{--                            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)--}}
        {{--                        });--}}
        {{--                    });--}}
        {{--                });--}}
        {{--            </script>--}}
    @endsection
</div>
