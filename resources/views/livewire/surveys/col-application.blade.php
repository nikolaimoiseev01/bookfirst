<div style="display: flex; flex-direction: column; gap: 20px;">

    <div
        @if(count($participation->user->survey) > 0)
            style="display: none;"
        @endif
        id="survey_wrap"
    >
        @if(count($participation->user->survey) == 0)
            <div class="survey_small_wrap">
{{--                <a title="Свернуть" class="link tooltip hideButton" id="hideButton">--}}
{{--                    <svg id="_Слой_1" data-name="Слой 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 50 50">--}}
{{--                        <path--}}
{{--                            d="m45.8,50c-1.1,0-2.1-.4-2.9-1.2L1.2,7.1C-.4,5.5-.4,2.8,1.2,1.2S5.5-.4,7.1,1.2l41.7,41.7c1.6,1.6,1.6,4.3,0,5.9-.8.8-1.9,1.2-3,1.2Z"--}}
{{--                            style="fill: #020203;"/>--}}
{{--                        <path--}}
{{--                            d="m4.2,50c-1.1,0-2.1-.4-2.9-1.2-1.6-1.6-1.6-4.3,0-5.9L42.9,1.2c1.6-1.6,4.3-1.6,5.9,0,1.6,1.6,1.6,4.3,0,5.9L7.1,48.8c-.8.8-1.9,1.2-2.9,1.2Z"--}}
{{--                            style="fill: #020203;"/>--}}
{{--                    </svg>--}}
{{--                </a>--}}
                @if($step == 1)
                    <div class="step_1_wrap">
                        <h4 class="title">Пожалуйста, оцените процесс создания заявки</h4>
                        <div class="stars_block_wrap">
                            <svg width="22px" id="Capa_12" data-name="Capa 1" xmlns="http://www.w3.org/2000/svg"
                                 viewBox="0 0 296 296">
                                <path
                                    d="m148,0C66.39,0,0,66.39,0,148s66.39,148,148,148,148-66.39,148-148S229.6,0,148,0Zm0,280c-36.26,0-69.14-14.7-93.02-38.44-9.54-9.48-17.63-20.41-23.93-32.42-9.6-18.29-15.04-39.09-15.04-61.14,0-72.78,59.21-132,132-132,34.52,0,65.99,13.33,89.53,35.1,12.21,11.29,22.29,24.84,29.56,40,8.27,17.24,12.91,36.54,12.91,56.9,0,72.78-59.21,132-132,132Z"
                                    style="fill: #ed4343;"/>
                                <path
                                    d="m163.64,187.61c17.55,3.67,33.32,13.54,44.4,27.79l12.63-9.82c-13.4-17.24-32.49-29.18-53.76-33.63-34.2-7.15-70.15,6.05-91.59,33.63l12.63,9.82c17.72-22.79,47.42-33.7,75.68-27.79Z"
                                    style="fill: #ed4343;"/>
                                <circle cx="98.67" cy="115" r="16" style="fill: #ed4343;"/>
                                <circle cx="197.67" cy="115" r="16" style="fill: #ed4343;"/>
                            </svg>
                            <x-stars-rating model="stars" inputrating="0"/>
                            <svg width="22px" id="Capa_1" data-name="Capa 1" xmlns="http://www.w3.org/2000/svg"
                                 viewBox="0 0 296 296">
                                <path
                                    d="m148,0C66.39,0,0,66.39,0,148s66.39,148,148,148,148-66.39,148-148S229.6,0,148,0Zm0,280c-36.26,0-69.14-14.7-93.02-38.44-9.54-9.48-17.63-20.41-23.93-32.42-9.6-18.29-15.04-39.09-15.04-61.14,0-72.78,59.21-132,132-132,34.52,0,65.99,13.33,89.53,35.1,12.21,11.29,22.29,24.84,29.56,40,8.27,17.24,12.91,36.54,12.91,56.9,0,72.78-59.21,132-132,132Z"
                                    style="fill: #ffa500;"/>
                                <path
                                    d="m97.41,114.4c8.6,0,15.6,6.6,15.6,15.6h16c0-18-14.17-31.6-31.6-31.6s-31.6,13.6-31.6,31.6h16c0-9,7-15.6,15.6-15.6Z"
                                    style="fill: #ffa500;"/>
                                <path
                                    d="m198.58,114.4c8.6,0,15.6,6.6,15.6,15.6h16c0-18-14.17-31.6-31.6-31.6s-31.6,13.6-31.6,31.6h16c0-9,7-15.6,15.6-15.6Z"
                                    style="fill: #ffa500;"/>
                                <path
                                    d="m147.71,229.99c30.95,0,60.62-15.83,77.6-42.11l-13.44-8.68c-15.6,24.13-44.13,37.6-72.69,34.31-22.26-2.57-42.85-15.39-55.07-34.31l-13.44,8.68c14.79,22.89,39.72,38.41,66.68,41.52,3.46.4,6.92.6,10.36.6Z"
                                    style="fill: #ffa500;"/>
                            </svg>
                        </div>
                        <a wire:click="after_first_step" class="show_preloader_on_click button">Отправить</a>
                    </div>
                @elseif($step == 2)
                    <div class="step_2_wrap">
                        <x-chat-textarea x-show="open" model="text"
                                         placeholder="Пожалуйста, опишите, что было не так. Каждый день мы стараемся быть лучше, поэтому нам важно это знать."
                                         attachable="false" sendable="false"></x-chat-textarea>
                        <div class="buttons_wrap">
                            <a wire:click="step_back" class="link">Назад</a>
                            <a wire:click="save_survey" class="show_preloader_on_click button">Сохранить</a>
                        </div>

                    </div>
                @endif
            </div>
        @endif


    </div>

    <div class="top_links_wrap">
        <a target="_blank" href="{{route('help_collection')}}#application_pay" class="help_link link">
            Инструкция по этой странице
        </a>
{{--        <a class="link" style="display: none;" id="showButton">Пройти опрос</a>--}}
    </div>

{{--    @push('page-js')--}}
{{--        <script>--}}
{{--            // Функция для установки значения в куки--}}
{{--            function setCookie(name, value, days) {--}}
{{--                var expires = "";--}}
{{--                if (days) {--}}
{{--                    var date = new Date();--}}
{{--                    date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));--}}
{{--                    expires = "; expires=" + date.toUTCString();--}}
{{--                }--}}
{{--                document.cookie = name + "=" + (value || "") + expires + "; path=/";--}}
{{--            }--}}

{{--            // Функция для получения значения из куки--}}
{{--            function getCookie(name) {--}}
{{--                var nameEQ = name + "=";--}}
{{--                var ca = document.cookie.split(';');--}}
{{--                for (var i = 0; i < ca.length; i++) {--}}
{{--                    var c = ca[i];--}}
{{--                    while (c.charAt(0) == ' ') c = c.substring(1, c.length);--}}
{{--                    if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);--}}
{{--                }--}}
{{--                return null;--}}
{{--            }--}}

{{--            // Функция для скрытия блока и установки значения в куки--}}
{{--            document.getElementById("hideButton").addEventListener("click", function () {--}}
{{--                document.getElementById("survey_wrap").style.display = "none";--}}
{{--                document.getElementById("showButton").style.display = "block";--}}
{{--                setCookie("divHidden", "true", 365); // Скрыто на год--}}
{{--            });--}}

{{--            // Функция для отображения блока и изменения значения в куки--}}
{{--            document.getElementById("showButton").addEventListener("click", function () {--}}
{{--                document.getElementById("survey_wrap").style.display = "block";--}}
{{--                document.getElementById("showButton").style.display = "none";--}}
{{--                setCookie("divHidden", "", -1); // Удаляем куки--}}
{{--            });--}}

{{--            // Проверяем значение куки при загрузке страницы--}}
{{--            var divHidden = getCookie("divHidden");--}}
{{--            if (divHidden === "true") {--}}
{{--                document.getElementById("survey_wrap").style.display = "none";--}}
{{--                document.getElementById("showButton").style.display = "block";--}}
{{--            } else {--}}
{{--                document.getElementById("survey_wrap").style.display = "block";--}}
{{--                document.getElementById("showButton").style.display = "none";--}}
{{--            }--}}

{{--        </script>--}}
{{--    @endpush--}}
</div>
