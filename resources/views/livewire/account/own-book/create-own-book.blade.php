<div x-data class="chat_wrap create_ownbook_wrap">

    <form
        wire:submit.prevent="confirm_save(Object.fromEntries(new FormData($event.target)))"
        enctype="multipart/form-data"
    >
        <div class="create_ownbook_form_wrap container">
            @csrf
            <div class="inputs_wrap">

                <div class="part_wrap input_general_wrap">
                    <div class="header_wrap">
                        <h4>Общая информация</h4>
                    </div>

                    <div class="inputs_row">
                        <div class="input-group">
                            <p>Автор*</p>
                            <input wire:model="author_name" type="text"
                                   class="@if(in_array('author_name', $error_fields) && !$author_name) danger @endif">
                        </div>
                        <div class="input-group">
                            <p>Название книги*</p>
                            <input wire:model="book_title" type="text"
                                   class="@if(in_array('title', $error_fields) && !$book_title) danger @endif">
                        </div>
                    </div>

                </div>

                <div class="part_wrap input_inside_wrap">

                    <div class="header_wrap">
                        <h4>Внутренний блок</h4>
                        <div class="switch-wrap">
                            <input wire:model="inside_type" type="radio" value="by_file" id="by_file"
                                   name="inside_type">
                            <label for="by_file">Файлом</label>
                            <input wire:model="inside_type" checked type="radio" id="by_system" value="by_system"
                                   name="inside_type">
                            <label for="by_system">Из системы</label>
                        </div>
                    </div>

                    <div class="details_wrap">

                        <div x-cloak x-show="$wire.inside_type == 'by_file'">
                            <div class="by_file_wrap @if(in_array('pages', $error_fields) && !$inside_files) danger @endif">
                                <div wire:ignore
                                     class="filepond_wrap">
                                    <input name="inside_files" class="filepond_inside" type="file"/>
                                </div>
                                @if(($inside_files ?? null) && count($inside_files) > 0)
                                    <div class="inputs_row">
                                        <p>Страниц в моей книге</p>
                                        <input wire:model="pages" min="30" class="number-input" type="number">
                                        <x-question-mark>
                                            Минмальное количество страниц: 30.
                                        </x-question-mark>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div x-cloak
                             x-show="$wire.inside_type == 'by_system'">
                            @livewire('components.work-choose', ['works_already_in'=>null])
                        </div>


                        <div x-cloak
                             x-show="$wire.pages > 0">
                            <div class="inputs_row">
                                <p>Макет полностью готов?</p>

                                <x-question-mark>
                                    Макет можно считать готовым, если файл полностью подготовлен к общепринятым
                                    правилам
                                    издания.
                                    Никакая редактура не потребуется.
                                </x-question-mark>

                                <div class="switch-wrap">
                                    <input wire:model="inside_ready" checked type="radio" id="inside_ready_1"
                                           value="1"
                                           name="inside_ready">
                                    <label for="inside_ready_1">Да</label>

                                    <input wire:model="inside_ready" type="radio" value="0" id="inside_ready_0"
                                           name="inside_ready">
                                    <label for="inside_ready_0">Нет</label>
                                </div>
                            </div>

                            <div x-cloak
                                 x-show="$wire.inside_ready == '0'"
                                 x-transition.opacity.duration.500ms
                                 class="inside_works_wrap inputs_row">
                                <div class="check-block">
                                    <label for="need_design"><p>Дизайн текста</p></label>
                                    <x-question-mark>
                                        Подбор шрифтов, цветов, общего оформления и подготовка к печати
                                    </x-question-mark>
                                    <input wire:model="need_design" id="need_design" type="checkbox">
                                </div>
                                <div class="check-block">
                                    <label for="need_check"><p>Проверка правописания</p></label>
                                    <x-question-mark>
                                        Услуга проверки пунктуации и орфографии
                                    </x-question-mark>
                                    <input wire:model="need_check" id="need_check" type="checkbox">
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="part_wrap input_cover_wrap">
                    <div class="header_wrap">
                        <h4>Обложка</h4>
                        <div class="switch-wrap">
                            <input wire:model="cover_ready" type="radio" value="0" id="cover_ready_0"
                                   name="cover_ready">
                            <label for="cover_ready_0">Нужна помощь</label>

                            <input wire:model="cover_ready" checked type="radio" id="cover_ready_1" value="1"
                                   name="cover_ready">
                            <label for="cover_ready_1">Готовая</label>


                        </div>
                    </div>

                    <div class="details_wrap cover_need_wrap">
                        @if($cover_ready === '1')
                            <x-chat-textarea
                                model="cover_comment"
                                placeholder="Пожалуйста, прикрепите готовую обложку. Если есть какие-то комментарии, пожалуйста, дайте нам знать."
                                attachable="true"
                                sendable="false"></x-chat-textarea>
                        @else
                            <x-chat-textarea
                                model="cover_comment"
                                placeholder="Здесь необходимо описать Ваше видение будущей обложки. Любые наработки можно также прикрепить файлами. Чем точнее будет описание, тем лучше будут работы дизайнера :)"
                                attachable="true"
                                sendable="false"></x-chat-textarea>
                        @endif


                    </div>

                </div>

                <div class="part_wrap input_print_wrap">
                    <div class="header_wrap">
                        <div class="check-block">
                            <input wire:model="need_print" id="need_print" type="checkbox">
                            <label for="need_print"><h4>Мне необходимы печатные экземпляры</h4>
                            </label>
                        </div>
                    </div>

                    <div x-cloak
                         x-show="$wire.need_print"
                         x-transition.opacity.duration.500ms
                         class="print_info_wrap">
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
                </div>

                <div class="part_wrap input_promo_wrap">
                    <div class="header_wrap">
                        <div id="promo_need" class="check-block">
                            <input wire:model="need_promo" id="need_promo" type="checkbox">
                            <label for="need_promo">
                                <h4>Мне необходимо продвижение</h4>
                            </label>
                        </div>
                    </div>

                    <div x-cloak
                         x-show="$wire.need_promo"
                         x-transition.opacity.duration.500ms
                         class="details_wrap promo_var_wrap">
                        <div class="check-block">
                            <label for="promo_type_1"><p>Вариант 1</p></label>
                            <input wire:model="promo_type" checked="" value="1" name="promo_type" id="promo_type_1"
                                   type="radio">
                            <x-question-mark>
                                Разместить в блоке 'Наши авторы'
                            </x-question-mark>
                        </div>

                        <div class="check-block">
                            <label for="promo_type_2"><p>Вариант 2</p></label>
                            <input wire:model="promo_type" checked="" value="2" name="promo_type" id="promo_type_2"
                                   type="radio">
                            <x-question-mark>
                                Бессрочное размещение на всех страницах сайта и соц. сетях.
                            </x-question-mark>
                        </div>
                    </div>

                </div>

            </div>

            <div class="calc-outputs">
                <div class="header_wrap">
                    <h2 class="header">Стоимость
                    </h2>
                    @if(!($pages ?? null) || $pages === 0)
                        <p class="no_pages_alert">
                            Чтобы рассчитывать цены в заявке, нужно загрузить произведения одним из способов (файлом
                            или из нашей системы). В случае добавления файлов формата не 'Docx', необходимо вручную
                            указать кол-во страниц после загрузки.
                        </p>
                    @endif
                </div>


                @if(($pages ?? null) && $pages > 0)
                    <div class="details_wrap">
                        <div class="participation-price out_inside_wrap">
                            <div id="part_price" class="price-number">
                                {{number_format($price_inside, 0, '', ' ')}}
                            </div>
                            <p class="price-desc">Работа с макетом ({{$pages}} стр.)</p>
                            <p x-cloak
                               x-show="$wire.need_design"
                               x-transition.opacity.duration.500ms
                               class="price-desc out_design_wrap">В т.ч. дизайн текста: {{$price_design}}</p>
                            <p x-cloak
                               x-show="$wire.need_check"
                               x-transition.opacity.duration.500ms
                               class="price-desc out_check_wrap">В т.ч. проверка правописания: {{$price_check}}</p>
                        </div>

                        <div class="participation-price out_print_wrap"
                             x-cloak
                             x-show="$wire.price_print > 0"
                             x-transition.opacity.duration.500ms>
                            <div id="print_price" class="price-number">
                                <p class="participation-price-plus price-desc">+</p>
                                {{number_format($price_print, 0, '', ' ')}}
                                @if($prints <= 4)
                                    <x-question-mark>
                                        Стоимость 1,2,3,4 экземпляров будет одинаковая, так как мы печатаем книгу
                                        изначально
                                        на листе
                                        А3.
                                    </x-question-mark>
                                @endif
                            </div>
                            <p class="price-desc">Печать ({{$prints}} экз.)</p>
                            @if($prints > 1)
                                <p class="price-desc">{{ceil($price_print / $prints)}}/шт.</p>
                            @endif
                        </div>

                        <div class="participation-price out_cover_wrap"
                             x-cloak
                             x-show="$wire.cover_ready === '0'"
                             x-transition.opacity.duration.500ms>
                            <div id="print_price" class="price-number">
                                <p class="participation-price-plus price-desc">+</p>
                                {{number_format($price_cover, 0, '', ' ')}}
                            </div>
                            <p class="price-desc">Создание обложки</p>
                        </div>

                        <div class="participation-price out_promo_wrap"
                             x-cloak
                             x-show="$wire.need_promo"
                             x-transition.opacity.duration.500ms>
                            <div class="price-number">
                                <p class="participation-price-plus price-desc">+</p>
                                {{number_format($price_promo, 0, '', ' ')}}
                            </div>
                            <p class="price-desc">Продвижение</p>
                        </div>
                    </div>
                @else

                @endif

                @if(($pages ?? null) && $pages > 0)
                    <div class="price-total">
                        <p class="price-desc">Итого:&nbsp;</p>
                        <div id="total_price" class="price-number">{{number_format($price_total, 0, '', ' ') }}</div>
                        <p class="price-desc rub">&nbsp;руб.</p>
                    </div>
                @endif
            </div>

        </div>

        <div class="final_buttons_wrap">
            <div>
                <button type="submit" class="button">Отправить заявку</button>
                <p style="font-size: 20px; color: #bdbdbd"><i>* - обязательны для заполнения</i></p>
            </div>
            <a href="{{route('help_own_book')}}" style="font-size: 20px;" target="_blank" class="link"><i>Нужна помощь</i></a>
        </div>


    </form>

    @push('page-js')
        <script>
            $('input[name="inside_ready"]').change(function () {
                $(".inside_works_wrap").slideToggle(500);
            })

            $("#need_design").change(function () {
                $(".out_design_wrap").slideToggle(500);
            });

            $("#need_check").change(function () {
                $(".out_check_wrap").slideToggle(500);
            });

            $('input[name="cover_ready"]').change(function () {
                $(".out_cover_wrap").slideToggle(500);
            })


            $("#need_print").change(function () {
                $(".print_info_wrap").slideToggle(500);
                $(".out_print_wrap").slideToggle(500);
            });


            $("#need_promo").change(function () {
                $(".promo_var_wrap").slideToggle(500);
                $(".out_promo_wrap").slideToggle(500);
            });

            function calculate_inside_files() {
                inside_files = [];
                $("[name='inside_files']").each(function () {
                    if ($(this).val() !== '') {
                        inside_files.push($(this).val())
                    }
                })
            @this.set('inside_files', inside_files)
            }

            $('.filepond_inside').filepond({
                server: {
                    url: '/myaccount/temp-uploads/inside_files',
                    headers: {
                        'X-CSRF-TOKEN': '{{csrf_token()}}'
                    }
                },
                onprocessfile: (file) => {
                    calculate_inside_files()
                @this.emit('count_doc_pages')
                },
                onremovefile: () => {
                    calculate_inside_files();
                @this.emit('count_doc_pages')
                },

                maxTotalFileSize: '30MB',
                labelMaxFileSizeExceeded: 'Размер превышен!',
                labelMaxFileSize: 'Максимальный: {filesize}',
                labelMaxTotalFileSizeExceeded: 'Макс. размер: 20мб!',
                labelMaxTotalFileSize: 'Максимум: {filesize}',
                labelIdle: `<p>Загрузите один файл <b>внутреннего блока.</b> <a class="link">Загрузить</a></p></br><p style="font-size: 20px; line-height: 25px; color: #ff5b5b;"><i>В файлах 'docx' мы попробуем определить кол-во страниц автоматически. <br>В случае ошибки необходимо указать кол-во вручную.</i></p>`,
            });

        </script>

        <script>

            document.addEventListener('livewire:load', function () {
                var timeOnPage = 0;
                setInterval(function() {
                    timeOnPage += 1; // увеличиваем время на странице каждую секунду
                    if (timeOnPage === 30) { // если пользователь находится на странице больше минуты (60 секунд)
                        window.livewire.emit('new_almost_complete_action')
                    }
                }, 1000); // вызываем каждую секунду

            })
        </script>
    @endpush


</div>
