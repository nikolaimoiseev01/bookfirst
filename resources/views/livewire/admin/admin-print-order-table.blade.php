<div>
    <style>
        .table td {
            vertical-align: middle;
            text-align: center;

        }

        th {
            text-align: center;
            vertical-align: middle !important;
        }
    </style>
    <table id="print_orders_table" class="table table-bordered table-hover">
        <thead>
        <tr>
            <th>Статус, руб.</th>
            <th>Автор</th>
            <th>ФИО адресата</th>
            <th>Адрес</th>
            <th>Телефон</th>
            <th>Кол-во</th>
            <th>Стоимость пересылки, руб.</th>
            <th>Трек-номер</th>
            <th>Логистика</th>
            <th>На страницу участия</th>
        </tr>
        </thead>
        <tbody>
        @foreach($participations as $participation)

            @if ($participation->printorder['books_needed'] ?? 0 > 0)
                <tr>
                    <td style="width: 10%; text-align: center;">
                        @if($participation->printorder['paid_at'])
                            <span style="color:#00cd00;">Оплачен</span>
                        @else
                            <span style="color:#e54c4c;">НЕ оплачен</span>
                        @endif
                    </td>
                    <td style="width: 10%; text-align: center;">
                        {{$participation['surname']}} {{$participation['name']}}
                    </td>
                    <td style="width: 10%;  text-align: center;">
                        {{$participation->printorder['send_to_name']}}
                    </td>
                    <td style="width: 10%;  text-align: center;">
                        {{print_address($participation->printorder)}}
                    </td>
                    <td>
                        {{$participation->printorder['send_to_tel']}}
                    </td>
                    <td>
                        {{$participation->printorder['books_needed']}}
                    </td>
                    <td style="width: 10%; text-align: center;">
                        <div>
                            @if(!!$participation->printorder['send_price'])
                                <div class="d-flex align-items-center justify-content-center" id="input_closed_wrap">
                                    <p style="margin-bottom: 0;
                                    @if ($show_input_send === 1)
                                        display:none;
                                    @else
                                        display:inline;
                                    @endif
                                        " id="send_price_{{$participation->printorder['id']}}">{{$participation->printorder['send_price']}}</p>
                                    <i wire:ignore style="font-size: 20px; " id="send_{{$participation->printorder['id']}}"
                                       class="show_input p-0 ml-1 far fa-edit"></i>
                                </div>

                                <div wire:ignore class="d-none align-items-center"
                                     id="input_shown_wrap_send_{{$participation->printorder['id']}}">
                                    <input wire:model="send_price.{{$participation->printorder['id']}}"
                                           class="form-control"
                                           style="-webkit-appearance: none !important;" type="text">
                                    <a id="send_save_btn_{{$participation->printorder['id']}}" class="save_btn btn p-0 ml-1"
                                       wire:click.prevent="save_send_price({{$participation->printorder['id']}})">
                                        <i style="font-size: 24px;" class="far fa-save"></i>
                                    </a>
                                </div>
                            @else
                                <div class="d-flex align-items-center">
                                    <input wire:model="send_price.{{$participation->printorder['id']}}"
                                           class="form-control"
                                           style="-webkit-appearance: none !important;" type="text">
                                    <a class="btn p-0 ml-1"
                                       wire:click.prevent="save_send_price({{$participation->printorder['id']}})"><i
                                            style="font-size: 28px;" class="far fa-save"></i></a>

                                    @endif
                                </div>
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
                                       href="{{tracking_link($participation->printorder)}}">
                                        {{$participation->printorder['track_number']}}
                                    </a>
                                    <i wire:ignore style="font-size: 20px; " id="{{$participation->printorder['id']}}"
                                       class="show_input p-0 ml-1 far fa-edit"></i>
                                </div>

                                <div wire:ignore class="d-none align-items-center"
                                     id="input_shown_wrap_{{$participation->printorder['id']}}">
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
                                </div>
                    </td>
                    <td>
                        <p>{{$participation->printorder['logistic']}}</p>
                            <select wire:model="logistic.{{$participation->printorder['id']}}" name="logistic.{{$participation->printorder['id']}}">
                                <option value="pochta">Почта</option>
                                <option value="cdek">СДЭК</option>
                            </select>
                            <a class="btn p-0 ml-1"
                               wire:click.prevent="save_logistic({{$participation->printorder['id']}})"><i
                                    style="font-size: 28px;" class="far fa-save"></i></a>
                    </td>
                    <td style="width: 10%; text-align: center;">
                        <a target="_blank" href="{{route('user_participation', ['participation_id' => $participation['id']])}}"><i class="fas fa-share"></i></a>
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
                id = $(this).attr('id').substring(9, 15);

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
