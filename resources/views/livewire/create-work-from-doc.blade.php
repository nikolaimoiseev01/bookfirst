<div style="width: 100%;">
    <h2 style="display: flex; align-items: center; font-size: 37px;">Правила:


        <a target="_blank"
           style="    margin-bottom: 0; box-shadow: none; padding: 1px 15px; margin-top: 5px;margin-left: 20px; font-size: 18px;"
           href="{{route('help_account')}}#account_2" class="button">Подробная инструкция</a></h2>
    <li style="list-style-type: disc;"><p>Файл должен содержать только произведения</p></li>
    <li style="list-style-type: disc;"><p>Файл должен быть строго формата <b>.docx</b></p></li>
    <li style="list-style-type: disc;"><p>Не нужно начинать каждое произведение с новой страницы</p></li>
    <li style="list-style-type: disc;"><p><b>Название</b> должно быть выделено жирным</p></li>
    <li style="margin-bottom: 10px; list-style-type: disc;"><p>Текст нежирный (внутри текста нельзя вставлять жирные
            символы)</p></li>

    <p>Если все правила учтены, мы готовы анализировать Ваш файл :)</p><br>
    <div style="align-items: center; display: flex; flex-wrap: wrap; margin-top: 20px;">
        <div style="margin-right: 20px; margin-bottom: 10px;" class="input-file">
            <label for="work_file" class="custom-file-upload">
                Выбрать файл
            </label>
            <span wire:ignore id="label_work_file"><p></p></span>

            <input accept=".docx" style="display: none;" wire:model="file" name="work_file" class="custom-file-input"
                   id="work_file"
                   type="file"/>
            <div style="margin: 0 20px; display: none;" wire:loading wire:target="file"><p style="font-size: 22px;">Файл
                    загружается</p></div>
        </div>

        <a wire:click.prevent="read_doc" style="margin: 0 0 10px 0;" id="start_doc_scan" class="button">Распознать</a>
    </div>

    @if (count($works) > 0)
        <h2 style="font-size: 37px; margin-top: 20px;">Вот, что мы нашли:</h2>
        <p style="margin-bottom: 20px; font-size: 24px;"><i>Каждое произведение можно отредактировать или удалить в
                случае ошибки автоматического анализа.</i></p>
        <br><a wire:click.prevent="save_all_work()" class="button">Сохранить найденные произведения</a>
        <div class="works-prev">
            @foreach($works as $work)
                <div id="prev_work_{{$loop->index}}" class="container work-prev">
                        <span style="right: 40px;" id="change_{{$loop->index}}" class="change-work-prev tooltip"
                              title="Редактировать">
                           <svg style="width:20px" id="Слой_1" data-name="Слой 1" xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 401 398.98">
                            <path
                                d="M370.11,251.91a10,10,0,0,0-10,10v88.68a30,30,0,0,1-30,30H49.93a30,30,0,0,1-30-30V90.32a30,30,0,0,1,30-30h88.68a10,10,0,1,0,0-20H49.93A50,50,0,0,0,0,90.32V350.57A50,50,0,0,0,49.93,400.5H330.16a50,50,0,0,0,49.93-49.93V261.89A10,10,0,0,0,370.11,251.91Z"
                                transform="translate(0 -1.52)"/>
                            <path
                                d="M376.14,14.68a45,45,0,0,0-63.56,0L134.41,192.86a10,10,0,0,0-2.57,4.39l-23.43,84.59a10,10,0,0,0,12.29,12.3l84.59-23.44a10,10,0,0,0,4.4-2.56L387.86,90a45,45,0,0,0,0-63.56Zm-220,184.67L302,53.52l47,47L203.19,246.38Zm-9.4,18.85,37.58,37.58-52,14.39Zm227-142.36-10.6,10.59-47-47,10.6-10.59a25,25,0,0,1,35.3,0l11.73,11.71A25,25,0,0,1,373.74,75.84Z"
                                transform="translate(0 -1.52)"/>
                        </svg>
                       </span>
                    <span id="delete_{{$loop->index}}" wire:click.prevent="delete_work({{$loop->index}})"
                          class="delete-work-prev tooltip" title="Удалить">
                        <svg id="Слой_1" data-name="Слой 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                            <path
                                d="M256,512C114.84,512,0,397.16,0,256S114.84,0,256,0,512,114.84,512,256,397.16,512,256,512Zm0-475.43C135,36.57,36.57,135,36.57,256S135,475.43,256,475.43,475.43,377,475.43,256,377,36.57,256,36.57Z"
                                transform="translate(0 0)"/><path
                                d="M347.43,365.71a18.22,18.22,0,0,1-12.93-5.35L151.64,177.5a18.29,18.29,0,0,1,25.86-25.86L360.36,334.5a18.28,18.28,0,0,1-12.93,31.21Z"
                                transform="translate(0 0)"/><path
                                d="M164.57,365.71a18.28,18.28,0,0,1-12.93-31.21L334.5,151.64a18.29,18.29,0,0,1,25.86,25.86L177.5,360.36A18.22,18.22,0,0,1,164.57,365.71Z"
                                transform="translate(0 0)"/>
                        </svg>
                    </span>

                    <span style="display:none;" id="save_change_{{$loop->index}}"
                          class="change-work-prev save_work tooltip" title="Сохранить">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 477.87 477.87">
                            <path
                                d="M238.93,0C107,0,0,107,0,238.93S107,477.87,238.93,477.87s238.94-107,238.94-238.94S370.83.14,238.93,0Zm0,443.73c-113.11,0-204.8-91.69-204.8-204.8s91.69-204.8,204.8-204.8,204.8,91.69,204.8,204.8S352,443.61,238.93,443.73Z"
                                transform="translate(0 0)"/><path
                                d="M370.05,141.53a17.09,17.09,0,0,0-23.72,0h0l-158.6,158.6-56.2-56.2A17.07,17.07,0,1,0,107,267.65l.42.41,68.27,68.27a17.07,17.07,0,0,0,24.13,0L370.47,165.66A17.07,17.07,0,0,0,370.05,141.53Z"
                                transform="translate(0 0)"/></svg>
                    </span>
                    <h2 id="title_{{$loop->index}}"
                        style="overflow-wrap: anywhere; font-size: 30px; margin: 10px 30px 0 10px;">{{$work['title']}}</h2>
                    <input style="    font-size: 24px; display:none; margin: 10px 40px 0 10px;" type="text"
                           value="{{$work['title']}}"
                           name="title_input_{{$loop->index}}" id="title_input_{{$loop->index}}">
                    <p style="max-height: 300px; overflow: auto; margin:10px;"
                       id="text_{{$loop->index}}">{!! $work['text'] !!}</p>
                    <div class="editable_div" id="text_input_{{$loop->index}}"
                         style="max-height: 300px; overflow: auto; height: 100%; margin:10px; display:none;" id="text"
                         contenteditable="true">
                        <p>{!! $work['text'] !!}</p>
                    </div>

                </div>
            @endforeach

        </div>

        <a wire:click.prevent="save_all_work()" class="button">Сохранить найденные произведения</a>

    @endif

    <script>
        $('.change-work-prev').click(function () {
            var id = $(this).attr('id').split('_').pop().trim();
            $('#title_input_' + id).toggle();
            $('#title_' + id).toggle();
            $('#text_input_' + id).toggle();
            $('#text_' + id).toggle();
            $('#change_' + id).toggle();
            $('#delete_' + id).toggle();
            $('#save_change_' + id).toggle();
            $('#textarea_input_' + id).val($('#text_input_' + id + ' p').html());
            window.livewire.rescan();
            window.livewire.start();
        });

        $('.editable_div').on('blur keyup paste', function () {
            var id = $(this).attr('id').split('_').pop().trim();
            $('#textarea_input_' + id).val($('#text_input_' + id + ' p').html());
        });


        document.addEventListener('livewire:load', function () {

            $(".save_work").click(function () {
                window.livewire.rescan();
                window.livewire.start();
                var id = $(this).attr('id').split('_').pop().trim();
            @this.edit_work(id, $('#title_input_' + id).val(), $('#text_input_' + id + ' p').html());
            @this.work_stat_function();
            })

            window.addEventListener('works_stat', event => {
            @this.work_stat_function();
            })

        })
    </script>


</div>
