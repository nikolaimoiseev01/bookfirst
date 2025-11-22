<div class="create_work_doc_page_wrap">


    <div class="header_wrap">
        <h4>Правила:</h4>
        <a target="_blank" href="{{route('help_account')}}#account_2" class="button">
            Подробная инструкция
        </a>
    </div>


    <li><p>Файл должен содержать только произведения</p></li>
    <li><p>Файл должен быть строго формата <b>.docx</b></p></li>
    <li><p>Не нужно начинать каждое произведение с новой страницы</p></li>
    <li><p><b>Название</b> должно быть выделено жирным</p></li>
    <li><p>Текст нежирный (внутри текста нельзя вставлять жирные символы)</p></li>
    <br>
    <a href="/admin_files/Пример загрузки из файла.docx"  class="link">Скачать пример файла</a>
    <br><br>
    <p>Если все правила учтены, мы готовы анализировать Ваш файл :)</p><br>

    <div class="buttons_wrap">
        <div class="input_file_wrap">
            <label for="work_file" class="link">
                Выбрать файл
            </label>
            <input accept=".docx" wire:model="file" name="work_file" class="custom-file-input"
                   id="work_file"
                   type="file"/>

        </div>

        <div wire:loading.remove wire:target="file">
            <span wire:ignore  id="label_work_file"><p></p></span>
        </div>

        <div style="margin: 0 20px; display: none;" wire:loading wire:target="file"><p style="font-size: 22px;">Файл загружается...</p></div>

        <a wire:click.prevent="read_doc" id="start_doc_scan" class="show_preloader_on_click button">Распознать</a>
    </div>

    @if (count($works) > 0)
        <div class="works_block_wrap">

            <div class="header">
                <h4>Вот, что мы нашли:</h4>
                <p>
                        Каждое произведение можно отредактировать или удалить в случае ошибки автоматического анализа.

                </p>
            </div>

            <div class="works_wrap">
                @foreach($works as $work)
                    <div class="container work_wrap @if($work['editing']) editing @endif">

                        @if(!$work['editing'])
                            <div class="title_wrap">
                                <h2 id="title_{{$loop->index}}" class="title">
                                    {{Str::limit($work['title'], 20)}}
                                </h2>
                                <div class="icons_wrap">
                                    <span wire:click.prevent="make_editable({{$loop->index}})"
                                          class="material-symbols-outlined edit_work tooltip"
                                          title="Редактировать">edit</span>

                                    <span class="material-symbols-outlined delete_work tooltip"
                                          title="Удалить"
                                          wire:click.prevent="delete_work({{$loop->index}})">cancel</span>
                                </div>
                            </div>

                            <p class="text">{!! nl2br($work['text']) !!}</p>


                        @else
                            <div class="title_input_wrap">
                                <input type="text" wire:model="works.{{$loop->index}}.title">
                            </div>
                            <textarea class="editable_div" wire:model="works.{{$loop->index}}.text"></textarea>
                            <a wire:click.prevent="save({{$loop->index}})" class="button">Сохранить изменения</a>
                        @endif
                    </div>
                @endforeach

            </div>

            <a wire:click.prevent="save_all_work()"
               class="button show_preloader_on_click save_all_button">{{$back_after_work_adding['button_text'] ?? 'Сохранить'}}</a>


        </div>
    @endif

    @push('page-js')

    @endpush

</div>
