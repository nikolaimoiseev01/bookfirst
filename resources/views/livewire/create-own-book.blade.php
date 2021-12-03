<div class="create_book_wrap">
    <style>
        .input-block {
            display: flex;
        }

        .send-wrap {
            width: 33px;
            position: relative;
            border: 1px #6dc4b1 solid;
            border-left: none;
            border-radius: 0 5px 5px 0;
            display: flex;
            align-items: flex-end;
            justify-content: center;
        }

        .filepond--item {
            width: calc(30% - 0.5em);
        }

        .filepond--file-status-sub {
            font-size: 12px;
        }

        .pre_cover_files .filepond--root {
            max-height: 200px;
            margin-top: -76px;
        }

        .pre_cover_files .filepond--drop-label {
            visibility: hidden;
        }

        .pre_cover_files .filepond--browser.filepond--browser {
            visibility: hidden;
        }

        .pre_cover_files .filepond--list.filepond--list {
            border: none;
        }

        .pre_cover_wrap textarea, .pre_cover_wrap div {
            border: none;
        }

        .pre_cover_wrap {
            border-radius: 5px;
            border: 1px #6dc4b1 solid;
        }


    </style>
    <form
        wire:submit.prevent="save_own_book(Object.fromEntries(new FormData($event.target)))"
        enctype="multipart/form-data"
    >
        <div class="create-participation-form">
            <div>
                <div class="container">
                    @csrf

                    <div class="participation-inputs">


                        {{----------- БЛОК ОБЩЕЙ ИНФОРМАЦИИ -----------}}
                        <div id='general_block' class="ob-applic-block">
                            <h2>Общая информация</h2>
                            <div style="margin-bottom: 0;" class="participation-inputs-row">
                                <div class="input-group">
                                    <p>Автор</p>
                                    <input wire:model="author_name" type="text" name="author_name" id="author_name">
                                </div>
                                <div class="input-group">
                                    <p>Название книги</p>
                                    <input wire:model="book_title" type="text" name="book_title"
                                           id="book_title">
                                </div>
                            </div>
                        </div>
                        {{----------- // БЛОК ОБЩЕЙ ИНФОРМАЦИИ -----------}}

                        {{----------- ВНУТРЕННИЙ БЛОК -----------}}
                        <div wire:ignore id='inide_block' class="ob-applic-block">

                            <h2 style="display: flex; align-items: center; justify-content: space-between;">Внутренний
                                блок
                                <div style="margin-left: 10px;" class="switch-wrap">
                                    <input checked type="radio" value="by_file" id="by_file" name="upload_type"
                                           class="show-hide">
                                    <label for="by_file">
                                        Файлом
                                    </label>

                                    <input type="radio" value="by_system" id="by_system" name="upload_type"
                                           class="show-hide">
                                    <label for="by_system">
                                        Из системы
                                    </label>
                                </div>
                            </h2>
                            <div class="upload_type" id="block_by_file">
                                <div style="border-radius: 5px; border: 1px #6dc4b1 solid;" wire:ignore>
                                    <style>
                                        .upload_type.filepond--root {
                                            height: 120px !important;
                                        }

                                        .upload_type .filepond--drop-label {
                                            height: 120px !important;
                                        }
                                    </style>
                                    <input accept multiple name="adfiles" class="filepond_inside" type="file"/>
                                    <div style="display: none; margin-left: 10px; margin-bottom: 10px;"
                                         class="page_error_wrap">
                                        <a class="link">Указать страницы вручную</a>
                                        <div style="display: inline-block;">
                                            <div style="display: none;" class="page_error_number_wrap">
                                                <p>Страниц в книге: </p>
                                                <input id="real_pages"
                                                       style="margin-left: 10px; width: 50px; font-size: 18px; height: 30px;"
                                                       type="number">
                                                <svg id="save_page_error" fill="#47AF98"
                                                     style="margin-left:8px; width: 24px;" viewBox="0 0 477.87 477.87">
                                                    <path
                                                        d="M238.93,0C107,0,0,107,0,238.93S107,477.87,238.93,477.87s238.94-107,238.94-238.94S370.83.14,238.93,0Zm0,443.73c-113.11,0-204.8-91.69-204.8-204.8s91.69-204.8,204.8-204.8,204.8,91.69,204.8,204.8S352,443.61,238.93,443.73Z"/>
                                                    <path
                                                        d="M370.05,141.53a17.09,17.09,0,0,0-23.72,0h0l-158.6,158.6-56.2-56.2A17.07,17.07,0,1,0,107,267.65l.42.42,68.27,68.26a17.07,17.07,0,0,0,24.13,0L370.47,165.66A17.07,17.07,0,0,0,370.05,141.53Z"/>
                                                </svg>
                                                <span style="display: flex;" class="tooltip"
                                                      title="Пересчитать с файлов">
                                                <svg id="cancel_page_error" fill="#e45151"
                                                     style="margin-left:8px; width: 24px;" viewBox="0 0 512 512"><path
                                                        d="M256,512C114.84,512,0,397.16,0,256S114.84,0,256,0,512,114.84,512,256,397.16,512,256,512Zm0-475.43C135,36.57,36.57,135,36.57,256S135,475.43,256,475.43,475.43,377,475.43,256,377,36.57,256,36.57Z"/><path
                                                        d="M347.43,365.71a18.22,18.22,0,0,1-12.93-5.35L151.64,177.5a18.29,18.29,0,0,1,25.86-25.86L360.36,334.5a18.28,18.28,0,0,1-12.93,31.21Z"/><path
                                                        d="M164.57,365.71a18.28,18.28,0,0,1-12.93-31.21L334.5,151.64a18.29,18.29,0,0,1,25.86,25.86L177.5,360.36A18.22,18.22,0,0,1,164.57,365.71Z"/></svg>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div id="block_by_system" style="display: none; flex-flow: column;"
                                 class="upload_type participation-inputs-row">
                                <div>
                                    <div class="add-work-block">
                                    <span class="question-mark tooltip"
                                          title="Порядок произведений можно менять перетаскиванием">
                                       <svg id="question-circle" data-name="Capa 1" xmlns="http://www.w3.org/2000/svg"
                                            viewBox="0 0 40.12 40.12">
                                            <path
                                                d="M19.94,12.14c1.85,0,3,1,3,2.66,0,3-5.41,3.87-5.41,7.55a2,2,0,0,0,2,2.07c2.05,0,1.8-1.51,2.54-2.6,1-1.45,5.6-3,5.6-7,0-4.36-3.89-6.19-7.86-6.19-3.77,0-7.24,2.69-7.24,5.73a1.85,1.85,0,0,0,2,1.88C17.52,16.23,16,12.14,19.94,12.14Z"/>
                                            <path d="M22.14,29a2.54,2.54,0,1,0-2.54,2.54A2.55,2.55,0,0,0,22.14,29Z"/>
                                            <path
                                                d="M40.12,20.06A20.06,20.06,0,1,0,20.06,40.12,20.08,20.08,0,0,0,40.12,20.06ZM2,20.06A18.06,18.06,0,1,1,20.06,38.12,18.08,18.08,0,0,1,2,20.06Z"/>
                                        </svg>
                                    </span>

                                        <div class="add-work-button">
                                            <a class="add-work-button-link">
                                                <svg id="Слой_1" data-name="Слой 1" xmlns="http://www.w3.org/2000/svg"
                                                     viewBox="0 0 512 512">
                                                    <path
                                                        d="M256,512C114.84,512,0,397.16,0,256S114.84,0,256,0,512,114.84,512,256,397.16,512,256,512Zm0-480C132.48,32,32,132.48,32,256S132.48,480,256,480,480,379.52,480,256,379.52,32,256,32Z"/>
                                                    <path d="M368,272H144a16,16,0,0,1,0-32H368a16,16,0,0,1,0,32Z"/>
                                                    <path
                                                        d="M256,384a16,16,0,0,1-16-16V144a16,16,0,0,1,32,0V368A16,16,0,0,1,256,384Z"/>
                                                </svg>
                                                Добавить
                                            </a>

                                            <div class="custom-scroll work-menu">
                                                <h2 style="font-size: 24px; margin-bottom: 10px;">Мои произведения:</h2>
                                                @if(count($user_works) < 1)
                                                    <p style="    font-size: 19px; line-height: 22px; margin-bottom: 10px;">
                                                        У
                                                        Вас еще нет произведений!
                                                        Для того, чтобы учавствовать в сборниках, произведения должны
                                                        сначала
                                                        быть добавлены в нашу систему,
                                                        а затем выбраны из этого списка.</p>
                                                @endif
                                                @foreach($user_works as $work)
                                                    <div id="work-container-{{$work['id']}}" class="container">
                                                        <p>{{Str::limit($work['title'], 20)}}</p>
                                                        <div class="one-work-button">
                                                            <a class="add_remove_buttons">
                                                                <svg
                                                                    id="not-in-{{$work['id']}}"
                                                                    viewBox="0 0 448.13 490.8">
                                                                    <path class="cls-1"
                                                                          d="M231.7,3.13a10.67,10.67,0,0,0-15.09,15.08L443.73,245.35,216.59,472.46a10.67,10.67,0,0,0,14.82,15.35l.26-.27L466.34,252.88a10.66,10.66,0,0,0,0-15.09Z"
                                                                          transform="translate(-21.34 0)"/>
                                                                    <path class="cls-1"
                                                                          d="M274.36,237.79,39.7,3.13A10.67,10.67,0,0,0,24.61,18.21L251.73,245.35,24.59,472.46a10.67,10.67,0,0,0,14.82,15.35l.27-.27L274.34,252.88A10.67,10.67,0,0,0,274.36,237.79Z"
                                                                          transform="translate(-21.34 0)"/>
                                                                    <path
                                                                        d="M224.14,490.68a10.67,10.67,0,0,1-7.55-18.22L443.73,245.35,216.59,18.23A10.66,10.66,0,0,1,231.67,3.15L466.34,237.82a10.65,10.65,0,0,1,0,15.08L231.68,487.57A10.69,10.69,0,0,1,224.14,490.68Z"
                                                                        transform="translate(-21.34 0)"/>
                                                                    <path
                                                                        d="M32.14,490.68a10.67,10.67,0,0,1-7.55-18.22L251.73,245.35,24.59,18.23A10.68,10.68,0,0,1,39.7,3.13L274.36,237.8a10.65,10.65,0,0,1,0,15.08L39.7,487.54A10.68,10.68,0,0,1,32.14,490.68Z"
                                                                        transform="translate(-21.34 0)"/>
                                                                </svg>

                                                                <svg data-rows="{{$work['rows']}}" class="in"
                                                                     id="in-{{$work['id']}}" data-name="Capa 1"
                                                                     xmlns="http://www.w3.org/2000/svg"
                                                                     viewBox="0 0 229.15 226.47">
                                                                    <path
                                                                        d="M92.36,223.55c7.41,7.5,23.91,5,25.69-6.78,11-73.22,66.38-135,108.24-193.19C237.9,7.45,211.21-7.87,199.75,8.07,161.49,61.25,113.27,117.21,94.41,181.74c-21.56-22-43.2-43.85-67.38-63.21-15.31-12.26-37.21,9.35-21.74,21.74C36.79,165.5,64,194.92,92.36,223.55Z"
                                                                        transform="translate(0 -1.34)"/>
                                                                </svg>

                                                            </a>
                                                        </div>
                                                    </div>
                                                @endforeach
                                                <div class="add-to-stystem-wrap">
                                                    <a onclick="location.href='{{route('work.create')}}';"
                                                       class="fast-load link">
                                                        <svg id="Слой_1" data-name="Слой 1"
                                                             xmlns="http://www.w3.org/2000/svg"
                                                             viewBox="0 0 448 448">
                                                            <path
                                                                d="M408,184H272a8,8,0,0,1-8-8V40a40,40,0,0,0-80,0V176a8,8,0,0,1-8,8H40a40,40,0,0,0,0,80H176a8,8,0,0,1,8,8V408a40,40,0,0,0,80,0V272a8,8,0,0,1,8-8H408a40,40,0,0,0,0-80Z"
                                                                transform="translate(0 0)"/>
                                                        </svg>
                                                        Добавить вручную
                                                    </a>
                                                    <a onclick="location.href='{{route('create_from_doc')}}';"
                                                       class="fast-load link">
                                                        <svg id="Слой_1" viewBox="0 0 404.85 511">
                                                            <g id="surface1">
                                                                <path
                                                                    d="M329.27,3A12.38,12.38,0,0,0,320.38-1H121C84.26-1,53.89,29.24,53.89,66V443c0,36.78,30.37,67,67.15,67H391.6c36.78,0,67.14-30.24,67.14-67V143.66a13.27,13.27,0,0,0-3.58-8.64Zm3.57,39.62,84.31,88.5h-54.8a29.39,29.39,0,0,1-29.51-29.37ZM391.6,485.32H121C98,485.32,78.58,466.19,78.58,443V66c0-23.08,19.26-42.33,42.46-42.33H308.16v78a54,54,0,0,0,54.19,54.06h71.71V443A42.67,42.67,0,0,1,391.6,485.32Z"
                                                                    transform="translate(-53.89 1)"/>
                                                                <path
                                                                    d="M357.9,400.15H154.74a12.35,12.35,0,1,0,0,24.69H358a12.35,12.35,0,1,0-.13-24.69Z"
                                                                    transform="translate(-53.89 1)"/>
                                                                <path
                                                                    d="M247.31,355.84a12.25,12.25,0,0,0,18,0l72.33-77.64a12.31,12.31,0,0,0-18-16.79l-51,54.68V181.31a12.34,12.34,0,0,0-24.68,0V316.09l-50.86-54.68a12.31,12.31,0,0,0-18,16.79Z"
                                                                    transform="translate(-53.89 1)"/>
                                                            </g>
                                                        </svg>
                                                        Добавить файлом
                                                    </a>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="works-to-go">
                                            <input wire:model="number_works" style="display: none" name="number_works"
                                                   value="0" id="number_works" type="number">
                                            <input style="display: none" name="any_works_check"
                                                   id="any_works_check" type="number">
                                            <input wire:model="toc_rows" style="display: none" name="toc_rows"
                                                   id="toc_rows"
                                                   type="number">
                                            <input wire:model="toc_pages" style="display: none" name="toc_pages"
                                                   id="toc_pages"
                                                   type="number">
                                        </div>
                                    </div>

                                </div>
                            </div>

                            <div style="display: flex; align-items: center; margin-top: 10px;">
                                <p>Макет полностью готов?</p>
                                <div style="margin-left: 10px;" class="switch-wrap">
                                    <input checked type="radio" id="inside_status_yes" name="inside_status"
                                           class="up-down">
                                    <label for="inside_status_yes">
                                        Да
                                    </label>

                                    <input type="radio" id="inside_status_no" value="show" name="inside_status"
                                           class="up-down">
                                    <label for="inside_status_no">
                                        Нет
                                    </label>
                                </div>
                                <span style="margin-left: 10px; display:flex;" class="tooltip"
                                      title="Макет можно считать готовым, если файл полностью подготовлен к общепринятым правилам издания. Никакая редактура не потребуется.">
                                       <svg id="question-circle" data-name="Capa 1" xmlns="http://www.w3.org/2000/svg"
                                            viewBox="0 0 40.12 40.12">
                                            <path
                                                d="M19.94,12.14c1.85,0,3,1,3,2.66,0,3-5.41,3.87-5.41,7.55a2,2,0,0,0,2,2.07c2.05,0,1.8-1.51,2.54-2.6,1-1.45,5.6-3,5.6-7,0-4.36-3.89-6.19-7.86-6.19-3.77,0-7.24,2.69-7.24,5.73a1.85,1.85,0,0,0,2,1.88C17.52,16.23,16,12.14,19.94,12.14Z"/>
                                            <path d="M22.14,29a2.54,2.54,0,1,0-2.54,2.54A2.55,2.55,0,0,0,22.14,29Z"/>
                                            <path
                                                d="M40.12,20.06A20.06,20.06,0,1,0,20.06,40.12,20.08,20.08,0,0,0,40.12,20.06ZM2,20.06A18.06,18.06,0,1,1,20.06,38.12,18.08,18.08,0,0,1,2,20.06Z"/>
                                        </svg>
                                    </span>
                            </div>

                            <div style="margin-top: 10px; display: none" id="check_needed"
                                 class="inside_status check-block">
                                <label for="textcheck_needed"><p style="margin:0;">Проверка правописания</p></label>
                                <input style="margin-left: 0;" id="textcheck_needed" type="checkbox">

                                <label for="textdesign_needed"><p style="margin:0;">Дизайн текста</p></label>
                                <input style="margin-left: 0;" checked id="textdesign_needed" type="checkbox">
                            </div>

                        </div>
                        {{----------- // ВНУТРЕННИЙ БЛОК -----------}}

                        {{----------- БЛОК ОБЛОЖКИ -----------}}
                        <div wire:ignore id='cover_block' class="ob-applic-block">
                            <h2 style="z-index: 9; position: relative; display: flex; align-items: center; justify-content: space-between;">
                                Обложка
                                <div style="margin-left: 10px;" class="switch-wrap">
                                    <input checked type="radio" value="cover_status_no" id="cover_status_no"
                                           name="cover_status"
                                           class="show-hide">
                                    <label for="cover_status_no">
                                        Нужна помощь
                                    </label>

                                    <input type="radio" id="cover_status_yes" value="cover_status_yes"
                                           name="cover_status"
                                           class="show-hide">
                                    <label for="cover_status_yes">
                                        Готовая
                                    </label>
                                </div>
                            </h2>

                            <div id="block_cover_status_yes"
                                 style="border-radius: 5px; border: 1px #6dc4b1 solid; width: 100%; display:none;  margin-right: 20px;"
                                 class="cover_status">

                                <input accept multiple name="cover_files" class="filepond_cover" type="file"/>
                            </div>

                            <div id="block_cover_status_no" class="cover_status">
                                <div class="pre_cover_files">
                                    <input accept multiple name="pre_cover_files"
                                           class="pre_cover_files filepond_pre_cover"
                                           type="file"/>
                                </div>
                                <div wire:ignore class="pre_cover_wrap block_cover_status_no input-block">

                                <textarea wire:ignore
                                          style="border-right: none;  resize: none; border-radius: 5px 0 0 5px; height: 100px; width: 100%;"
                                          placeholder="Здесь необходимо описать Ваше видение будущей обложки. Любые наработки можно также прикрепить файлами. Чем точнее будет описание, тем лучше будут работы дизайнера :)"
                                          name="" cols="30"
                                          id="cover_comment"
                                          rows="10"></textarea>
                                    <div class="send-wrap">
                                        <span style="margin-bottom: 7px;" class="tooltip" title="Прикрепить файл">
                                        <svg style="position: inherit;" onclick="trigger_filepond_function()"
                                             class="attach_icon"
                                             viewBox="0 0 268.12 494.4"><path
                                                d="M247.2,0C173.29,0,113.14,60.13,113.14,134.06V387.87a16.39,16.39,0,1,0,32.78,0V134.06a101.28,101.28,0,0,1,202.56,0V395.73a66,66,0,0,1-65.89,65.89c-.27,0-.52.14-.79.16s-.51-.16-.79-.16a66,66,0,0,1-65.9-65.89v-157a32.09,32.09,0,1,1,64.18,0v149.1a16.39,16.39,0,0,0,32.78,0V238.77a64.87,64.87,0,1,0-129.74,0v157A98.78,98.78,0,0,0,281,494.4c.29,0,.52-.15.8-.16s.52.16.79.16a98.79,98.79,0,0,0,98.67-98.67V134.06C381.26,60.13,321.11,0,247.2,0Z"
                                                transform="translate(-113.14 0)"/></svg>
                                        </span>
                                    </div>
                                </div>
                            </div>


                        </div>
                        {{----------- // БЛОК ОБЛОЖКИ -----------}}


                        {{----------- БЛОК ПЕЧАТИ -----------}}
                        <div id='print_block' class="ob-applic-block">
                            <div id="print_need" class="check-block">
                                <input style="margin-left: 0;" id="prints-needed" name="prints-needed" class="up-down"
                                       type="checkbox">
                                <label for="prints-needed"><h2 style="margin:0;">Мне необходимы печатные экземпляры</h2>
                                </label>
                            </div>
                            <div wire:ignore style="margin-top: 10px; display: none" class="prints-needed ptint-block">

                                <div style="margin-bottom: 7px;">
                                    <p>Материял обложки:</p>
                                    <div style="margin-left: 10px;" class="switch-wrap">
                                        <input checked type="radio" value="cover_style_soft" id="cover_style_soft"
                                               name="cover_style">
                                        <label for="cover_style_soft">
                                            мягкая
                                        </label>

                                        <input type="radio" value="cover_style_hard" id="cover_style_hard"
                                               name="cover_style">
                                        <label for="cover_style_hard">
                                            твердая
                                        </label>
                                    </div>
                                </div>

                                <div>
                                    <p>Цвет обложки:</p>
                                    <div style="margin-left: 10px;" class="switch-wrap">
                                        <input checked type="radio" value="cover_color_yes" id="cover_color_yes"
                                               name="cover_color">
                                        <label for="cover_color_yes">
                                            цветная
                                        </label>

                                        <input type="radio" value="cover_color_no" id="cover_color_no"
                                               name="cover_color">
                                        <label for="cover_color_no">
                                            черно-белая
                                        </label>
                                    </div>
                                </div>


                                <div style="margin-top: 7px; margin-bottom: 7px;">
                                    <p>Цвет блока:</p>
                                    <div style="margin-left: 10px;" class="switch-wrap">
                                        <input checked type="radio" class="show-hide" value="inside_color_no"
                                               id="inside_color_no"
                                               name="color_pages">
                                        <label for="inside_color_no">
                                            черно-белый
                                        </label>

                                        <input type="radio" value="inside_color_yes" class="show-hide"
                                               id="inside_color_yes"
                                               name="color_pages">
                                        <label for="inside_color_yes">
                                            цветной
                                        </label>
                                    </div>
                                    <div
                                    <div style="display:inline-block;">
                                        <div style="display:none;" id="block_inside_color_yes" class="color_pages">
                                            <p>, цветных страниц: </p>
                                            <input id="color_pages" style="width: 50px; font-size: 18px; height: 30px"
                                                   type="number">
                                        </div>
                                    </div>
                                </div>

                                <div style="flex-direction: row;     align-items: center;"
                                     class="participation-inputs-row">
                                    <p style="    width: 35%;">Количество экземпляров:</p>
                                    <label for="prints-num"></label><input style="max-width: 80px; margin-right: 40px;"
                                                                           type="number"
                                                                           name="prints-num"

                                                                           value="1" id="prints-num">
                                    <div class="slider-wrap">
                                        <div id="slider-nonlinear" class="slider">
                                            <div id="custom-handle" class="ui-slider-handle">
                                                <div class="slider-tooltip"></div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <div style="flex-direction: row;     align-items: center;"
                                     class="participation-inputs-row">
                                    <div class="input-group">
                                        <p>ФИО получателя</p>
                                        <input wire:model="send_to_name" type="text"
                                               value="{{ Auth::user()->surname}} {{ Auth::user()->name}}"
                                               name="send_to_name" id="send_to_name">
                                    </div>
                                    <div class="input-group">
                                        <p>Адрес с индексом</p>
                                        <input wire:model="send_to_address" type="text" name="send_to_address"
                                               id="send_to_address">
                                    </div>

                                    <div class="input-group">
                                        <p>Телефон</p>
                                        <input wire:model="send_to_tel" type="number"
                                               name="send_to_tel"
                                               id="send_to_tel">
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{----------- // БЛОК ПЕЧАТИ -----------}}


                        {{----------- БЛОК ПРОДВИЖЕНИЯ -----------}}
                        <div wire:ignore id='promo_block' class="ob-applic-block">
                            <div id="promo_need" class="check-block">
                                <input style="margin-left: 0;" id="promo-needed" name="promo-needed" class="up-down"
                                       type="checkbox">
                                <label for="promo-needed"><h2 style="margin:0;">Мне необходимо продвижение</h2>
                                </label>
                            </div>

                            <div style="display:none;" class="promo-needed">
                                <div style="margin-top: 10px;" id="check_needed" class="check-block">
                                    <label for="promo_var_1"><p style="margin:0;">Вариант 1</p></label>
                                    <input checked value="500" style="margin-left: 0;" name="promo_input"
                                           id="promo_var_1"
                                           type="radio">
                                    <span style="display:flex;" class="tooltip"
                                          title="Разместить в блоке 'Наши авторы'">
                                       <svg onclick="trigger_filepond_function()" id="question-circle"
                                            data-name="Capa 1" xmlns="http://www.w3.org/2000/svg"
                                            viewBox="0 0 40.12 40.12">
                                            <path
                                                d="M19.94,12.14c1.85,0,3,1,3,2.66,0,3-5.41,3.87-5.41,7.55a2,2,0,0,0,2,2.07c2.05,0,1.8-1.51,2.54-2.6,1-1.45,5.6-3,5.6-7,0-4.36-3.89-6.19-7.86-6.19-3.77,0-7.24,2.69-7.24,5.73a1.85,1.85,0,0,0,2,1.88C17.52,16.23,16,12.14,19.94,12.14Z"/>
                                            <path d="M22.14,29a2.54,2.54,0,1,0-2.54,2.54A2.55,2.55,0,0,0,22.14,29Z"/>
                                            <path
                                                d="M40.12,20.06A20.06,20.06,0,1,0,20.06,40.12,20.08,20.08,0,0,0,40.12,20.06ZM2,20.06A18.06,18.06,0,1,1,20.06,38.12,18.08,18.08,0,0,1,2,20.06Z"/>
                                        </svg>
                                    </span>
                                </div>

                                <div style="margin-top: 10px;" id="check_needed" class="check-block">
                                    <label for="promo_var_2"><p style="margin:0;">Вариант 2</p></label>
                                    <input value="2000" style="margin-left: 0;" name="promo_input" id="promo_var_2"
                                           type="radio">
                                    <span style="display:flex;" class="tooltip"
                                          title="Бессрочное размещение на сайте и в соц. сетях.">
                                       <svg id="question-circle" data-name="Capa 1" xmlns="http://www.w3.org/2000/svg"
                                            viewBox="0 0 40.12 40.12">
                                            <path
                                                d="M19.94,12.14c1.85,0,3,1,3,2.66,0,3-5.41,3.87-5.41,7.55a2,2,0,0,0,2,2.07c2.05,0,1.8-1.51,2.54-2.6,1-1.45,5.6-3,5.6-7,0-4.36-3.89-6.19-7.86-6.19-3.77,0-7.24,2.69-7.24,5.73a1.85,1.85,0,0,0,2,1.88C17.52,16.23,16,12.14,19.94,12.14Z"/>
                                            <path d="M22.14,29a2.54,2.54,0,1,0-2.54,2.54A2.55,2.55,0,0,0,22.14,29Z"/>
                                            <path
                                                d="M40.12,20.06A20.06,20.06,0,1,0,20.06,40.12,20.08,20.08,0,0,0,40.12,20.06ZM2,20.06A18.06,18.06,0,1,1,20.06,38.12,18.08,18.08,0,0,1,2,20.06Z"/>
                                        </svg>
                                    </span>
                                </div>

                            </div>

                        </div>
                        {{----------- // БЛОК ПРОДВИЖЕНИЯ -----------}}


                    </div>

                    <div wire:ignore class="participation-outputs">
                        <h2>Стоимость</h2>
                        <div style="margin-bottom: auto; margin-top: 20px; margin-right: 10px; margin-left: 10px;"
                             id="no-price-wrap">
                            <p style="text-align: center; color: #4C4B46 !important;">
                                Чтобы расчитывать цены в заявке, нужно загрузить произведения одним из способов (файлом
                                или из нашей системы). В случае добавления файлов формата не 'Docx', необходимо вручную
                                указать кол-во страниц после загрузки.
                            </p>
                        </div>
                        <div style="display: flex; flex-direction: column; display: none;" id="price-parts-wrap">
                            <div class="participation-price">
                                <div style="display: flex">
                                    <h1 id="layout_work_price_price">300</h1>
                                    <h1>&nbsp;руб.</h1>
                                </div>
                                <div class="participation-price-desc">
                                    <div>
                                        <p>работа с макетом (<span id="pages">>0</span><span>&nbsp;стр.)</span></p>
                                    </div>

                                    <p style="display: none; line-height: 20px; font-size: 20px;" class="inside_status">
                                        <i>Включая:</i></p>
                                    <p style="display: none; line-height: 20px; font-size: 20px; margin-right: auto;"
                                       class="inside_status"><i>проверка
                                            правописания: <span id="text_check_price">123</span> руб.</i></p>
                                    <p style="display: none; line-height: 25px; font-size: 20px; margin-right: auto;"
                                       class="inside_status"><i>дизайн
                                            текста: <span id="text_design_price">123</span> руб.</i></p>

                                </div>
                            </div>
                            <div class="participation-price">
                                <div style="display: flex">
                                    <h1 id="participation_price">500</h1>
                                    <h1>&nbsp;руб.</h1>
                                </div>
                                <div class="participation-price-desc">
                                    <p>присвоение ISBN</p>
                                    </p>
                                </div>
                            </div>
                            <div style="display: none" id="print-price" class="prints-needed participation-price">
                                <div style="display: flex">
                                    <h1 id="print_price">300</h1>
                                    <h1>&nbsp;руб.</h1>
                                </div>
                                <div class="participation-price-desc">
                                    <div></div>
                                    <p>за печать (<span id="print_needed">1</span>&nbsp;экз.)</p></div>
                            </div>

                            <div id="cover-price-total" class="cover-needed participation-price">
                                <div style="display: flex">
                                    <h1 id="cover_price">1500</h1>
                                    <h1>руб.</h1>
                                </div>
                                <div class="participation-price-desc">
                                    <div></div>
                                    <p>создание обложки</p></div>
                            </div>


                            <div style="display: none" id="promo-needed" class="promo-needed participation-price">
                                <div style="display: flex">
                                    <h1 id="promo_price">0</h1>
                                    <h1> руб.</h1>
                                </div>
                                <div class="participation-price-desc">
                                    <div></div>
                                    <p>продвижение (вар.:&nbsp;<span id="promo_var_num"></span>)</p></div>
                            </div>

                        </div>

                        <div style="display: none;" id="price-total-wrap">
                            <div class="total_price participation-price">
                                <div style="display: flex">
                                    <h1 id="total_price">800</h1>
                                    <h1>&nbsp;руб.</h1>
                                </div>
                                <div class="participation-price-desc"><p>Итог</p></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <div style="width:100%; text-align: end">
            <button style="float: left; margin-right:20px;" type="submit" id="save_form" class="preloader_button button">
                <span class="button__text">Отправить заявку</span>
            </button>
            <a href="{{route('help_own_book')}}" style="font-size: 20px;" class="link"><i>Нужна помощь</i></a>
        </div>


    </form>

    <a style="display:none;" class="fast-load" id="go-to-part-page">Кнопка назад</a>

    {{--    <a style="display:none;" id="go-to-part-page" class="fast-load">Кнопка назад</a>--}}


    @section('page-js')

        {{----------------- FILEPOND (загрузка файлов, подсчет страниц) -----------------}}

        <script>

            {{Session(['back_after_add' => \Livewire\str(Request::url())])}}

            // --- Работа с загрузкой файлов
            function calculate_after_upload() {
                var totalFiles = $('.upload_type .filepond--item').length;
                var completedFiles = $('.upload_type .filepond--item[data-filepond-item-state="processing-complete"]').length;
                if (completedFiles === totalFiles) {
                    add_files_paths = $("[name='adfiles']");
                    add_files_to_php = '';
                    for (var i = 0; i < add_files_paths.length; i++) {
                        if ($(add_files_paths[i]).val() != '') {
                            add_files_to_php += $(add_files_paths[i]).val() + ';';
                        }
                    }
                @this.emit('count_doc_pages', add_files_to_php.slice(0, -1));
                }
            }

            function calculate_after_remove() {
                add_files_paths = $("[name='adfiles']");
                add_files_to_php = '';
                for (var i = 0; i < add_files_paths.length; i++) {
                    if ($(add_files_paths[i]).val() != '') {
                        add_files_to_php += $(add_files_paths[i]).val() + ';';
                    }
                }

                if (add_files_to_php.slice(0, -1) === '') {
                    pages_from_doc = 0;
                    calculation()
                } else {
                @this.emit('count_doc_pages', add_files_to_php.slice(0, -1));
                }
            }

            function make_cover_files_after_upload() {
                var totalFiles = $('.block_cover_status_yes .filepond--item').length;
                var completedFiles = $('.block_cover_status_yes .filepond--item[data-filepond-item-state="processing-complete"]').length;
                if (completedFiles === totalFiles) {
                    cover_files_paths = $("[name='cover_files']");
                    cover_files_to_php = '';
                    for (var i = 0; i < cover_files_paths.length; i++) {
                        if ($(cover_files_paths[i]).val() != '') {
                            cover_files_to_php += $(cover_files_paths[i]).val() + ';';
                        }
                    }
                    cover_files_to_php = cover_files_to_php.slice(0, -1)
                }

            }

            function make_cover_files_after_remove() {
                cover_files_paths = $("[name='cover_files']");
                cover_files_to_php = '';
                for (var i = 0; i < cover_files_paths.length; i++) {
                    if ($(cover_files_paths[i]).val() != '') {
                        cover_files_to_php += $(cover_files_paths[i]).val() + ';';
                    }
                }
                cover_files_to_php = cover_files_to_php.slice(0, -1)
            }


            function make_pre_cover_files_after_upload() {
                var totalFiles = $('.filepond_pre_cover .filepond--item').length;
                var completedFiles = $('.filepond_pre_cover .filepond--item[data-filepond-item-state="processing-complete"]').length;
                if (completedFiles === totalFiles) {
                    pre_cover_files_paths = $("[name='pre_cover_files']");
                    pre_cover_files_to_php = '';
                    for (var i = 0; i < pre_cover_files_paths.length; i++) {
                        if ($(pre_cover_files_paths[i]).val() != '') {
                            pre_cover_files_to_php += $(pre_cover_files_paths[i]).val() + ';';
                        }
                    }
                    pre_cover_files_to_php = pre_cover_files_to_php.slice(0, -1)
                }

            }

            function make_pre_cover_files_after_remove() {
                pre_cover_files_paths = $("[name='pre_cover_files']");
                pre_cover_files_to_php = '';
                for (var i = 0; i < pre_cover_files_paths.length; i++) {
                    if ($(pre_cover_files_paths[i]).val() != '') {
                        pre_cover_files_to_php += $(pre_cover_files_paths[i]).val() + ';';
                    }
                }
                pre_cover_files_to_php = pre_cover_files_to_php.slice(0, -1)
            }


            FilePond.registerPlugin(FilePondPluginFileValidateSize);
            FilePond.registerPlugin(FilePondPluginFileValidateType);

            $('.filepond_inside').filepond({
                server: {
                    url: '/myaccount/temp-uploads/adfiles',
                    headers: {
                        'X-CSRF-TOKEN': '{{csrf_token()}}'
                    }
                },
                onprocessfile: (file) => {
                    calculate_after_upload();
                },
                onremovefile: (file) => {
                    calculate_after_remove();
                },
                maxTotalFileSize: '20MB',
                allowFileTypeValidation: false,
                labelMaxFileSizeExceeded: 'Размер превышен!',
                labelMaxFileSize: 'Максимальный: {filesize}',
                labelMaxTotalFileSizeExceeded: 'Сумма размеров превышена!',
                labelMaxTotalFileSize: 'Максимум: {filesize}',
                labelIdle: `<p class="input_file_text_1" style="line-height: 28px;">Загрузите один или несколько файлов <b>внутреннего блока.</b></p></br><p class="input_file_text_1" style="line-height: 28px;"> Можно перести файлы сюда или&nbsp;</p><a style="line-height: 28px;" class="link">Выбрать вручную</a></br><p class="input_file_text_1" style="font-size: 22px; line-height: 25px; color: #ff5b5b;"><i>В файлах 'docx' мы попробуем определить кол-во страниц автоматически. <br>В случае ошибки необходимо указать кол-во вручную.</i></p>`,
            });

            $('.filepond_cover').filepond({
                server: {
                    url: '/myaccount/temp-uploads/cover_files',
                    headers: {
                        'X-CSRF-TOKEN': '{{csrf_token()}}'
                    }
                },
                onprocessfile: (file) => {
                    make_pre_cover_files_after_upload();
                },
                onremovefile: (file) => {
                    make_pre_cover_files_after_remove();
                },
                maxTotalFileSize: '20MB',
                labelMaxFileSizeExceeded: 'Размер превышен!',
                allowFileTypeValidation: false,
                labelMaxFileSize: 'Максимальный: {filesize}',
                labelMaxTotalFileSizeExceeded: 'Сумма размеров превышена!',
                labelMaxTotalFileSize: 'Максимум: {filesize}',
                labelIdle: `<p>Загрузите один или несколько файлов <b>готовой обложки</b>. </p></br><p> Можно перести файлы сюда или&nbsp;</p><a class="link">Выбрать вручную</a>`,
            });

            $('.filepond_pre_cover').filepond({
                server: {
                    url: '/myaccount/temp-uploads/pre_cover_files',
                    headers: {
                        'X-CSRF-TOKEN': '{{csrf_token()}}'
                    }
                },
                onprocessfile: (file) => {
                    make_pre_cover_files_after_upload();
                },
                onremovefile: (file) => {
                    make_pre_cover_files_after_remove();
                },
                maxTotalFileSize: '20MB',
                labelMaxFileSizeExceeded: 'Размер превышен!',
                allowFileTypeValidation: false,
                labelMaxFileSize: 'Максимальный: {filesize}',
                labelMaxTotalFileSizeExceeded: 'Сумма размеров превышена!',
                labelMaxTotalFileSize: 'Максимум: {filesize}',
                labelIdle: `<span id="file_pond_button"></span>`,
            });


            function trigger_filepond_function() {
                $('#file_pond_button').click();
            }

            // --- // Работа с загрузкой файлов


        </script>
        {{----------------- // FILEPOND (загрузка файлов, подсчет страниц) -----------------}}

        <script>

            var
                rows = 0,
                pages = 0,
                pages_from_doc = 0,

                inside_status = 2,
                cover_status = 1,

                cover_color = 0,
                cover_type = "",
                cover_comment = null,
                cover_files_to_php = '',
                pre_cover_files_to_php = '',
                pre_cover_files,

                print_needed = 0,
                tirag_coef = 0,
                pages_coef = 0,
                inside_color = 0,
                cover_color_coef = 0,
                cover_style_coef = 0,
                color_pages = 0,

                promo_var_num = null,

                text_check_price = 0,
                text_design_price = 0,
                cover_price = 1500,
                print_price = 0,
                layout_work_price = 500,
                promo_price = 0,
                total_price = 0;

            // -----------------------------PRICES---------------------------------------------
            $("#real_pages").keyup(function get_print_val() {
                pages_from_doc = $(this).val();
                calculation();
            })
            print_needed = $("#prints-num").val();

            function calculation() {

                // Убираем чекбоксы с помощи по макету, если макет готов
                if ($('#inside_status_yes').prop('checked')) {
                    inside_status = 2;
                    $('#textcheck_needed').prop('checked', false);
                    $('#textdesign_needed').prop('checked', false);
                } else {
                    inside_status = 1;
                }
                ;
                // ------------------------------------------------------


                // Считаем строчки и страницы из системы
                if ($('input[name=upload_type]:checked').val() === 'by_system') {
                    $('.works-to-go .container').each(function () {
                        rows = parseInt(rows) + parseInt($(this).attr('data-rows'));
                    });
                    if (rows > 0) {
                        rows -= 1;
                    } else {
                        rows = 0;
                    }
                    pages = Math.ceil(rows / 33);
                } else {

                    pages = pages_from_doc;
                }
                // -----------------------------------------------------

                if ($("#prints-needed").prop("checked") === false) {
                    print_needed = 0;
                }


                if ($("#textcheck_needed").prop("checked") === true) {
                    text_check_price = pages * 30;
                } else {
                    text_check_price = 0;
                }

                if ($("#textdesign_needed").prop("checked") === true) {
                    text_design_price = pages * 13;
                } else {
                    text_design_price = 0;
                }

                //Обрабатываем значение печати
                if (print_needed < 10) {
                    tirag_coef = 1
                } else if (print_needed < 50) {
                    tirag_coef = 0.95
                } else {
                    tirag_coef = 0.9
                }

                if (pages < 100) {
                    pages_coef = 1.8
                } else {
                    pages_coef = 1.2
                }


                if ($("#cover_color_yes").prop("checked") === true) {
                    cover_color_coef = 1;
                    cover_color = 1;
                } else {
                    cover_color_coef = 0.7;
                    cover_color = 0;
                }

                if ($("#cover_style_hard").prop("checked") === true) {
                    cover_style_coef = 2.1
                    cover_type = "hard"
                } else {
                    cover_style_coef = 1
                    cover_type = "soft"
                }


                if ($("#promo-needed").prop("checked") === true) {
                    if (typeof $('input[name=promo_input]:checked').val() == 'undefined') {
                        promo_price = 0
                    } else {
                        promo_price = $('input[name=promo_input]:checked').val()
                        if (promo_price === '2000') {
                            promo_var_num = 2
                        } else {
                            promo_var_num = 1;
                        }

                    }
                    ;

                } else {
                    promo_price = 0;
                    promo_var_num = null;
                }


                if ($("#inside_color_yes").prop("checked") === false) {
                    color_pages = 0
                }

                if ($('input[name=cover_status]:checked').val() === 'cover_status_yes') {
                    cover_price = 0;
                    cover_comment = null;
                    cover_status = 9;
                    $('#cover-price-total').slideUp();
                } else {
                    cover_price = 1500;
                    cover_status = 1;
                    cover_comment = $('#cover_comment').val();
                    $('#cover-price-total').slideDown();
                }
                // --------------------------------

                print_price = (pages - color_pages + (color_pages * 3)) * 0.7 * tirag_coef * cover_color_coef * cover_style_coef * pages_coef * print_needed * 2.2;


                total_price = parseInt(text_design_price) + parseInt(text_check_price) + 300 + 500 + parseInt(cover_price) + parseInt(Math.round(print_price)) + parseInt(promo_price);

                //--------------------------------

                console.log('--------------------------------');
                console.log('rows: ' + rows + '; pages: ' + pages);
                console.log('color_pages: ' + color_pages);
                console.log('tirag_coef: ' + tirag_coef);
                console.log('cover_color_coef: ' + cover_color_coef);
                console.log('cover_style_coef: ' + cover_style_coef);
                console.log('pages_coef: ' + pages_coef);
                console.log('print_needed: ' + print_needed);

                console.log('text_design_price: ' + text_design_price);
                console.log('text_check_price: ' + text_check_price);
                console.log('print_price: ' + Math.round(print_price));
                console.log('promo_price: ' + promo_price);
                console.log('cover_price: ' + cover_price);

                console.log('PRINT: ' + pages + ' - ' + color_pages + ' + ' + color_pages * 3 + ' * ' + 0.7 + ' * ' + tirag_coef + ' * '
                    + cover_color_coef + ' * ' + cover_style_coef + ' * ' + pages_coef + ' * ' + print_needed + ' * ' + 2.2 + ' = ' + print_price);
                console.log('TOTAL: ' + text_design_price + ' + ' + text_check_price + ' + ' + cover_price + ' + ' + 300 + ' + ' + 500 + ' + ' + Math.round(print_price) + ' = ' + total_price);
                console.log('--------------------------------');


                // Скрываем и показываем цены
                if (parseInt(pages) > 0) {
                    $('#price-parts-wrap').css('display', 'flex');
                    $('#price-total-wrap').show();
                    $('#no-price-wrap').hide();
                } else {
                    pages = 0;
                    $('#no-price-wrap').show();
                    $('#price-parts-wrap').css('display', 'none');
                    $('#price-total-wrap').hide();
                }

                // Скрываем и показываем исправлений страниц
                if ($("[name='adfiles']").val() != '') {
                    $('.page_error_wrap').show();

                    $('.page_error_wrap a').on('click', function () {
                        $('.page_error_number_wrap').css('display', 'flex');
                        $('.page_error_wrap a').hide();
                    })

                    $('#cancel_page_error').on('click', function () {
                        $('.page_error_number_wrap').css('display', 'none');
                        $('.page_error_wrap a').show();
                        add_files_to_php = '';
                        for (var i = 0; i < add_files_paths.length; i++) {
                            if ($(add_files_paths[i]).val() != '') {
                                add_files_to_php += $(add_files_paths[i]).val() + ';';
                            }
                        }

                        if (add_files_to_php.slice(0, -1) === '') {
                            pages_from_doc = 0;
                            calculation()
                        } else {

                            window.livewire.emit('count_doc_pages', add_files_to_php.slice(0, -1));
                        }
                    })

                    $('#save_page_error').on('click', function () {
                        $('.page_error_number_wrap').css('display', 'none');
                        $('.page_error_wrap a').show();
                    })

                } else {
                    $('.page_error_wrap').hide();
                }
                // --------------------------------


                $('#pages').html(pages);
                $('#text_design_price').html(parseInt(text_design_price).toLocaleString());
                $('#text_check_price').html(parseInt(text_check_price).toLocaleString());
                $('#layout_work_price_price').html(parseInt(text_design_price + text_check_price + 300).toLocaleString());
                $('#print_needed').html(print_needed);
                $('#print_price').html(parseInt(Math.round(print_price)).toLocaleString());
                $('#promo_price').html(parseInt(promo_price).toLocaleString());
                $('#promo_var_num').html(promo_var_num);
                $('#total_price').html(parseInt(total_price).toLocaleString());


            };

            // Меняем цифру кол-ва экземпляров
            $("#prints-num").keyup(function get_print_val() {
                print_needed = $(this).val();
                calculation();
            })
            // ------------------------------------

            // Делает коммент для обложки
            $("#block_cover_status_no").keyup(function get_print_val() {
                calculation();
            })
            // ------------------------------------

            // Меняем цифру кол-ва цветных страниц
            $("#color_pages").keyup(function () {
                color_pages = $(this).val();
                calculation();
            })
            // ------------------------------------

            // Запускаем калькуляцию при обновлении livewire
            window.addEventListener('load_pages_from_doc', event => {
                pages_from_doc = event.detail.pages
                calculation();
            })
            // ------------------------------------

            // -----------------------------// PRICES---------------------------------------------


            {{-------- Отмечаем обязательные поля в завимости от чекбоксов ---------}}
            $('input').on('change', function () {
                if ($("#prints-needed").prop("checked") === false) {
                    print_needed = 0;
                    $("#prints-num").prop('required', false);
                    $("#send_to_name").prop('required', false);
                    $("#send_to_address").prop('required', false);
                    $("#send_to_tel").prop('required', false);
                } else {
                    print_needed = $("#prints-num").val();
                    $("#prints-num").prop('required', true);
                    $("#send_to_name").prop('required', true);
                    $("#send_to_address").prop('required', true);
                    $("#send_to_tel").prop('required', true);
                }
                calculation();
            });
            {{-------- // Отмечаем обязательные поля в завимости от чекбоксов ---------}}


            // -----------------------------Works Adding----------------------------------------------

            // ------- Показ списка работ ----------
            $menu = $('.work-menu');

            $(document).mouseup(e => {
                if (!$menu.is(e.target) // if the target of the click isn't the container...
                    && $menu.has(e.target).length === 0) {
                    $menu.removeClass('is-active');
                }
            });

            $('.add-work-button a').click(function (event) {
                event.preventDefault();
                $menu.addClass('is-active');
            });
            // ------- // Показ списка работ ----------

            // ------- Добавление и удаление ----------
            $(".one-work-button svg").on('click', function () {


                parts = $(this).attr("id").split('-');
                var id = parts.pop();
                rows = $(this).attr("data-rows");
                var text = $("#work-container-" + id + " p").text();
                $("#not-in-" + id).css('opacity', '0');
                $("#in-" + id).css('opacity', '1');

                $("#work-container-" + id + " a").css('pointer-events', "none");
                $("<div style=\"transition: none;\" data-rows='" + rows + "'id='work_to_go_" + id + "' class=\"container\">" +
                    "<p>" + text + "</p>" +
                    "<div id='remove_" + id + "' class='remove-work-wrap'>" +
                    "<a><img src='/img/cancel.svg'></a>" +
                    "</div>" + "<input style=\"display:none\" name=\"work[" + id + "]\" value=" + id + " type=\"number\">" +
                    "</div>").appendTo(".works-to-go");

                close_work = $('.remove-work-wrap')

                for (var i = 0; i < close_work.length; i++) {
                    close_work[i].addEventListener('click', function () {
                        parts = $(this).attr("id").split('_');
                        var id = parts.pop();
                        $("#work_to_go_" + id).remove();
                        $("#not-in-" + id).css('opacity', '1');
                        $("#in-" + id).css('opacity', '0');
                        $("#work-container-" + id + " a").css('pointer-events', "inherit");
                        if (!$(".work-menu").hasClass('is-active')) {
                            $('.work-menu').addClass('is-active');
                        }
                        calculation();
                    }, false);
                }
                calculation();
            });
            // ------- // Добавление и удаление ----------

            // ------------------------------// Works Adding---------------------------------------------


            // -----------------------------Slider---------------------------------------------

            var min = 0,
                max = 100,
                range = [],
                i = min,
                step = 1;

            do {

                range.push(i);
                i += step;


                if (i >= -1 && i < 5) {
                    step = 1;
                }

                if (i >= 5 && i < 50) {
                    step = 1;
                }

                if (i >= 50 && i < 100) {
                    step = 1;
                }

            } while (i <= max);

            var slider_min = 1,
                slider_max = range.length - 1,
                cur_val = $("#prints-num").val(),
                handle = $("#custom-handle");

            $("#slider-nonlinear").slider({
                values: [slider_min],
                min: slider_min,
                max: slider_max,
                animate: "slow",
                create: function () {
                    // handle.text( $( this ).slider( "value" ) );

                },
                slide: function (event, ui) {

                    c = ui.value;
                    $("#prints-num").val(c);

                    print_needed = c
                    handle.text("");
                    if (ui.value < 100) {
                        $('.ui-slider-handle').append('<div class="ui-slider-tooltip"> <p style="font-size: 18px;">' + ui.value + '</p></div>');
                    } else {
                        $('.ui-slider-handle').append('<div class="ui-slider-tooltip"> <p style="font-size: 18px;">' + ">100, подробнее" + '</p></div>');
                    }
                    ;
                    calculation();
                    // jQueryUI position
                    $('#ui-slider-tooltip').position({
                        of: $(".ui-slider-handle"),
                        at: 'center top',
                        my: 'center bottom'
                    });
                },
                stop: function (event, ui) {
                    if (ui.value < 100) {
                        $(".ui-slider-tooltip").remove();
                    }
                }
            });

            function delay(callback, ms) {
                var timer = 0;
                return function () {
                    var context = this, args = arguments;
                    clearTimeout(timer);
                    timer = setTimeout(function () {
                        callback.apply(context, args);
                    }, ms || 0);
                };
            }

            $('#prints-num').keyup(delay(function (e) {

                if (typeof c == 'undefined') {
                    if (typeof cur_val == 'undefined') {
                        cur_val = 1
                    }
                } else {
                    cur_val = c
                }
                ;
                var val = parseInt($(this).val());
                if (val > 100) {
                    val = 100;
                }

                var i = cur_val;
                if (cur_val < val) {


                    function myLoop() {
                        setTimeout(function () {
                            $(".ui-slider-tooltip").text(i);
                            $("#slider-nonlinear").slider("option", "values", [i]);
                            i++;
                            if (i - 1 < val) {
                                myLoop();
                            }
                        }, 5)
                    }

                    myLoop();
                    cur_val = parseInt($(this).val());
                } else {

                    var i = cur_val;

                    function myLoop() {
                        setTimeout(function () {
                            $(".ui-slider-tooltip").text(i);
                            $("#slider-nonlinear").slider("option", "values", [i]);

                            i--;
                            if (i + 1 > val) {
                                myLoop();
                            }
                        }, 5)
                    }

                    myLoop();
                    cur_val = parseInt($(this).val());
                }
                ;

            }, 400));

            // ----------------------------- // Slider---------------------------------------------

        </script>

        <script>

            document.addEventListener('livewire:load', function () {
                $("#save_form").click(function (event) {
                    event.preventDefault();
                    inside_type = $('input[name=upload_type]:checked').val();

                    if (inside_type == 'by_system') {
                        inside_type = 'системой'
                    } else {
                        inside_type = 'файлами'
                    }


                @this.set("inside_type", inside_type);
                    if (typeof add_files_to_php != 'undefined') {
                    @this.set("work_files", add_files_to_php.slice(0, -1));
                    } else {@this.set("work_files", 0)
                    }

                    works_to_php = '';
                    $('.works-to-go .container').each(function () {
                        parts = $(this).attr("id").split('_');
                        works_to_php += parts.pop() + ";"
                    })

                @this.set("works", works_to_php.slice(0, -1));

                @this.set("inside_status", inside_status);

                @this.set("text_design_price", text_design_price);
                @this.set("text_check_price", text_check_price);
                @this.set("cover_price", cover_price);
                @this.set("cover_status", cover_status);
                @this.set("pages", pages);

                    if (cover_price > 0) {
                    @this.set("cover_comment", cover_comment);
                    @this.set("pre_cover_files", pre_cover_files_to_php);
                    } else {@this.set("cover_files", cover_files_to_php)
                    }

                @this.set("promo_type", promo_var_num);
                @this.set("promo_price", promo_price);
                @this.set("total_price", total_price);
                @this.set("print_price", print_price);

                    if (print_price > 0) {
                    @this.set("cover_type", cover_type);
                    @this.set("cover_color", cover_color);
                    @this.set("color_pages", color_pages);
                    @this.set("books_needed", print_needed);
                    @this.set("send_to_name", $('#send_to_name').val());
                    @this.set("send_to_address", $('#send_to_address').val());
                    @this.set("send_to_tel", $('#send_to_tel').val());
                    }


                    Livewire.emit('save_own_book')

                });
            })

        </script>



    @endsection


</div>
