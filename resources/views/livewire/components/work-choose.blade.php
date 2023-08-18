<div>

    <div class="add-work-block">
        <x-question-mark>
            Произведения можно перемещать для изменения порядка
        </x-question-mark>
        <div

            class="add-work-button">
            <a
                x-on:click="$wire.set('show_menu', true)"
                class="add-work-button-link">
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

            <div
                x-on:click.outside="$wire.set('show_menu', false)"
                style="display: @if($show_menu) inherit @else none @endif"
                class="custom-scroll work-menu">

                <h2>Мои произведения:</h2>
                @if(count($works_to_choose) < 1 && count($works_to_part) < 1)
                    <p style="    font-size: 19px; line-height: 22px; margin-bottom: 10px;">
                        У
                        Вас еще нет произведений!
                        Для того, чтобы учавствовать в сборниках, произведения должны
                        сначала
                        быть добавлены в нашу систему,
                        а затем выбраны из этого списка.</p>
                @endif

                @if($works_to_choose)
                    <input id="work_search" placeholder="поиск..."
                           style="height: 30px; width: 100%; margin-bottom: 14px;" type="text">
                    @foreach($works_to_choose as $work)

                        <div id="work-container-{{$work['id']}}"
                             class="container">
                            <p>{{Str::limit($work['title'], 20)}}</p>
                            <div class="one-work-button">
                                <a class="add_remove_buttons">
                                    <svg
                                        wire:click.prevent="work_add({{$work['id']}})"
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


                                </a>
                            </div>
                        </div>
                    @endforeach
                @endif

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


        <div class="works-to-part">
            @foreach($works_to_part as $work)
                <div
                    id="work_to_part_{{$work['id']}}"
                    data-sort-id="{{$work['id']}}"
                    class="container">
                    <p> {{Str::limit($work['title'], 20)}}</p>
                    <div
                        onclick="work_remove({{$work['id']}})"
                        class="remove-work-wrap">
                        <a>
                            <img src="/img/cancel.svg">
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        @push('page-js')

            <script>
                $(".works-to-part").sortable({
                    placeholder: "to-drop",
                    revert: true,
                    start: function (event, ui) {
                        ui.item.addClass("start-anim")
                    },
                    stop: function (event, ui) {
                        ui.item.addClass("stop-anim")
                        sort_array = []
                        $('.works-to-part .container').each(function () {
                            sort_array.push($(this).attr('data-sort-id'))
                        })
                    @this.emit('updateWorkOrder', sort_array)
                    }
                });
            </script>

            <script>
                function work_remove(id) {
                @this.emit('work_remove', id)
                }
            </script>

            <script>
                $('#work_search').keyup(function () {
                    worksearch = this.value

                    $('.work-menu .container').each(function () {
                        if (worksearch != "") {
                            if ($(this).find("p:first").text().toLowerCase().indexOf(worksearch) == -1) {
                                $(this).css('display', 'none');
                            } else {
                                $(this).css('display', 'flex');
                            }
                        } else {
                            $(this).css('display', 'flex');
                        }
                    })
                })
            </script>
        @endpush
    </div>

</div>
