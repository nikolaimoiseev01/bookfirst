<div id="general_info_wrap" class="general_info_wrap part">
    {!! $page_style !!}
    <div class="line"></div>
    {!! $status_icon !!}
    <div class="container block_wrap">

        <div class=hero_wrap>
            <h2>Моя заявка</h2>
        </div>
        <div x-data class="info_wrap">

            <div class="top_wrap">
                <div class="part_part">
                    <div class="name">
                        {!! $app_text  !!}
                    </div>

                </div>

                <div class="print_part">
                    @if($own_book->printorder ?? null)
                        <div class="name">
                            <p><b>Параметры печати: </b>{{$print_text}}</p>
                        </div>
                        <div class="div">

                        </div>
                        <div class="name">
                            <p><b>Адрес: </b>{{print_address($own_book->printorder['id'])}}</p>
                        </div>
                        @if($own_book['own_book_status_id'] < 5)
{{--                            <a @click="$wire.need_print = !$wire.need_print" class="link">Редактировать заказ</a>--}}
                        @endif
                    @else
                        <h2>Печатные эезкемпляры не требуются.</h2>
                        @if($own_book['own_book_status_id'] < 5)
                            <a @click="$wire.need_print = !$wire.need_print" class="link">Создать заказ</a>
                        @endif

                    @endif
                </div>
            </div>

            <div x-cloak
                 x-show="$wire.need_print"
                 class="print_info_wrap">
                <div class="inputs_wrap">
                    <p><b>Заказ печатных экземпляров</b></p>
                    <div class="inputs_row">
                        <p>Количество экземпляров:</p>
                        <x-input.range-slider model="prints"/>
                    </div>
                    <div class="inputs_row">
                        <p>Стиль обложки</p>

                        <div class="switch-wrap">
                            <input wire:model="cover_type" checked type="radio" id="cover_type_soft" value="soft"
                                   name="cover_type">
                            <label for="cover_type_soft">мягкая</label>

                            <input wire:model="cover_type" type="radio" value="hard" id="cover_type_hard"
                                   name="cover_type">
                            <label for="cover_type_hard">твердая</label>
                        </div>
                    </div>
                    <div class="inputs_row">
                        <p>Цветность блока</p>

                        <div class="switch-wrap">
                            <input wire:model="inside_color" type="radio" value="0" id="inside_color_0"
                                   name="inside_color">
                            <label for="inside_color_0">черно-белый</label>

                            <input wire:model="inside_color" checked type="radio" id="inside_color_1" value="1"
                                   name="inside_color">
                            <label for="inside_color_1">цветной</label>
                        </div>
                        <p x-cloak x-show="$wire.inside_color == '1'">, цветных страниц</p>
                        <input x-cloak x-show="$wire.inside_color == '1'" name="pages_color"
                               wire:model="pages_color"
                               class="color_pages_input number-input" type="number">
                    </div>

                    <div class="inputs_row">
                        <div class="input-group">
                            <p>ФИО получателя*</p>
                            <input wire:model="send_to_name" type="text"
                                   class="@if(in_array('send_to_name', $error_fields) && !$send_to_name) danger @endif">
                        </div>
                        <div style="margin-bottom: 0;" class="input-group">
                            <p>Телефон*</p>
                            <input wire:model="send_to_tel" type="text"
                                   class="@if(in_array('send_to_tel', $error_fields) && !$send_to_tel) danger @endif">
                        </div>
                        <div style="margin-bottom: 0;" class="input-group">
                            <p>Страна*</p>
                            <input wire:model="send_to_country" type="text"
                                   class="@if(in_array('send_to_country', $error_fields) && !$send_to_country) danger @endif">
                        </div>
                    </div>
                    <div class="inputs_row">
                        <div class="input-group">
                            <p>Город*</p>
                            <input wire:model="send_to_city" type="text"
                                   class="@if(in_array('send_to_city', $error_fields) && !$send_to_city) danger @endif">
                        </div>
                        <div style="margin-bottom: 0;" class="input-group">
                            <p>Адрес*</p>
                            <input wire:model="send_to_address" type="text"
                                   class="@if(in_array('send_to_address', $error_fields) && !$send_to_address) danger @endif">
                        </div>
                        <div style="margin-bottom: 0;" class="input-group">
                            <p>Индекс*</p>
                            <input wire:model="send_to_index" type="text"
                                   class="@if(in_array('send_to_index', $error_fields) && !$send_to_index) danger @endif">
                        </div>
                    </div>
                </div>
                <div class="outputs_wrap">
                    <div class="price_grey">
                        {{$price_print}} руб.
                    </div>
                    <p>Печать ({{$prints}} экз.)</p>
                    <a wire:click="edit_printorder()" class="show_preloader_on_click button">Сохранить</a>
                    <a @click="$wire.need_print = !$wire.need_print" class="link">Закрыть</a>
                </div>
            </div>
        </div>

        @push('page-js')
            <script>
                $('.works_info_wrap a, .detailed_work_wrap a').on('click', function () {
                    $('.detailed_work_wrap').slideToggle()
                })
            </script>
        @endpush
    </div>
</div>
