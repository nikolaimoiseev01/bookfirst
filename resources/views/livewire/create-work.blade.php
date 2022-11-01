<div style="width:90%;display: flex;flex-wrap: wrap;">

    @section('page-style')
        <link rel="stylesheet" href="/css/cropjs.css" crossorigin="anonymous"/>
    @endsection

    <style>
        .modal {
            position: absolute;
            top: 0;
            left: 0;
        }

        .cropped_preview {
            overflow: hidden;
            width: 200px;
            height: 200px;
        }

        .modal-container {
            width: auto;
            max-width: fit-content;
        }

        .input-file span {
             border-left: none;
             padding: 0;
        }

        .cropped_result_block canvas {
            max-width: 300px;
        }
    </style>

    <div id="crop_preview" style="display: none" class="modal">
        <div class="modal-wrap">

            <div class="modal-container">
                <div style="display: flex; flex-direction: column;">
                    <h1 style="margin:0 0 20px 0; font-size: 35px;">Выберите миниатюру</h1>
                    <div style="display:flex; flex-wrap: wrap;">
                        <img src="{{$file_preview}}" id="work_img_preview" style="max-width: 300px;" alt="">
                        <div class="cropped_preview">
                        </div>
                    </div>
                </div>
                <div style="margin-top: 30px; display: flex">
                    <a href="" id="save_crop" style="width: 50%; text-align: center;"
                       class="button">Сохранить</a>
                    <a href="" id="cancel_crop" style="margin-left: 20px; width: 50%; text-align: center;"
                       class="button button__disabled">Отменить</a>
                </div>
            </div>
        </div>

    </div>


    <form wire:submit.prevent="storeWork(Object.fromEntries(new FormData($event.target)))"
          method="post"
          enctype="multipart/form-data"
        style="flex:1">
        <div class="add-work-form">
            @csrf
                <input style="margin-bottom: 20px;  width: 100%; margin-right: 20px;" wire:model="work_title" type="text" placeholder="Название" name="work_title" id="title">

            <textarea wire:model="work_text" id="poem-input" type="text" placeholder="Текст произведения"
                      name="work_text" id="text"></textarea>
            <div style="display: flex; justify-content: space-between; flex-wrap: wrap;">

{{--                <select style="flex: 1; margin-top:20px; margin-right: 10px;" wire:model="work_type" name="work_type" id="work_type">--}}
{{--                    <option value="" disabled selected="selected">Выберите тип</option>--}}
{{--                    @foreach($work_types->unique('type') as $work_type)--}}
{{--                        <option value="{{$work_type['type']}}">{{$work_type['type']}}</option>--}}
{{--                    @endforeach--}}
{{--                </select>--}}

{{--                <select style="flex: 1; margin-top:20px; margin-right: 10px;"  wire:model="work_topic" name="work_topic" id="work_topic">--}}
{{--                    <option value="" disabled selected="selected">Выберите тему</option>--}}
{{--                    @foreach($work_topics as $work_topic)--}}
{{--                        <option value="{{$work_topic['topic']}}">{{$work_topic['topic']}}</option>--}}
{{--                    @endforeach--}}
{{--                </select>--}}

{{--                <div style="margin-top:20px;"  class="input-file">--}}
{{--                    <label for="work_file" id="work_file_input_label" style="min-width: 253px; height: 100%;" class="custom-file-upload">--}}
{{--                        <span class="button__text">Добавить изображение</span>--}}
{{--                    </label>--}}
{{--                    --}}{{--                    <span wire:ignore="" id="label_work_file"><p></p></span>--}}

{{--                    <input accept=".png, .jpg" style="display: none;" wire:model="file" name="work_file"--}}
{{--                           class="custom-file-input" id="work_file" type="file">--}}

{{--                </div>--}}


            </div>
            <button style="width: 100%; margin-top:20px;" type="submit" class="button">Создать</button>
        </div>
        <input wire:model="symbols" style="display: none" type="number" name="symbols" id="symbols">
        <input wire:model="rows" style="display: none" type="number" name="rows" id="rows">
        <input wire:model="pages" style="display: none" type="number" min="1" name="pages" id="pages">
    </form>

    <div wire:ignore class="cropped_result_block">
{{--        <h2 style="margin: 0; font-size: 35px">Изображение:</h2>--}}
{{--                            <div style="margin: 0 20px; display: none;" wire:loading="" wire:target="file"><p--}}
{{--                                    style="font-size: 22px;">--}}
{{--                                    Файл загружается</p></div>--}}
        <div id="cropped_result"></div>
    </div>


</div>
</div>

@section('page-js')
    <script src="/js/cropjs.js" crossorigin="anonymous"></script>

    <script>

        // show file loader
        $('#work_file').change(function(e) {
            $('#work_file_input_label').addClass('button--loading');
            $('#work_file').attr('disabled', true);
            console.log('start');
        })
        document.addEventListener('update_preview', function () {

            // hide file loader
            $('#work_file_input_label').removeClass('button--loading');
            $('#work_file').attr('disabled', false);

            $('#crop_preview').fadeIn();
            const concernedElement = document.querySelector(".modal-container");
            document.addEventListener("mousedown", (event) => {
                if (!concernedElement.contains(event.target)) {
                    $('#crop_preview').fadeOut();
                }
            });


            const image = document.getElementById('work_img_preview');
            const cropper = new Cropper(image, {
                aspectRatio: 16 / 9,
                viewMode: 2,
                dragMode: 'move',
                preview: '.cropped_preview'
            });

            $('#cancel_crop').click(function (e) {
                e.preventDefault()
                $('#crop_preview').fadeOut();
            })

            $('#save_crop').click(function (e) {
                e.preventDefault()
                $('#crop_preview').fadeOut();
                // Crop

                croppedCanvas = cropper.getCroppedCanvas();
                var result = document.getElementById('cropped_result');
                result.innerHTML = '';
                result.appendChild(croppedCanvas);

                croppedCanvas.toBlob(function (blob) {
                    url = URL.createObjectURL(blob);
                    var reader = new FileReader();
                    reader.readAsDataURL(blob);
                    reader.onloadend = function () {
                    @this.set("cropped_img", reader.result);
                    }
                });

            })

        });
    </script>

    <script>
        document.addEventListener('livewire:load', function () {
            symbols = 0;
            pages = 1;
            $('#poem-input').bind('input propertychange', function () {
                var symbol = $(this).val().split('');
                symbols = $(this).val().length;
                symbols_to_rows = 0;
                rows = 1;
                $.each(symbol, function () {

                    if (this == '\n') {
                        rows++;
                        symbols_to_rows = 0;
                    } else {
                        if (symbols_to_rows > 50) {
                            rows++;
                            symbols_to_rows = 0;
                        } else {
                            symbols_to_rows++
                        }
                    }
                    ;

                    pages = Math.ceil(rows / 33);

                    $('#rows').val(rows);
                @this.set("rows", rows);
                    $('#symbols').val(symbols);
                @this.set("symbols", symbols);
                    $('#pages').val(pages);
                @this.set("pages", pages);
                    $('#symbols_to_rows').val(symbols_to_rows);
                });
            });
        });
    </script>
    @endsection
    </div>
