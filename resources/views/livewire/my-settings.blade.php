<div>
    @section('page-style')
        <link rel="stylesheet" href="/css/cropjs.css" crossorigin="anonymous"/>
    @endsection
    <style>
        .input-group {
            flex-direction: row;
            align-items: center;
        }

        .account-header {
            margin-bottom: 0;
        }

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
            padding: 30px;
            border-radius: 15px;
            background: white;
        }

        .input-file span {
            border-left: none;
            padding: 0;
        }

        .cropped_result_block canvas {
            max-width: 300px;
        }

        .input-file label:hover {
            background: none;
            color: var(--green);
        }
    </style>

    <div id="crop_preview" style="display: none" class="modal">
        <div class="modal-wrap">

            <div class="modal-container">
                <div style="display: flex; flex-direction: column;">
                    <h1 style="margin:0 0 20px 0; font-size: 35px;">Выберите миниатюру</h1>
                    <div style="display:flex; flex-wrap: wrap;">
                        <img src="{{$avatar_file_preview}}" id="work_img_preview" style="max-width: 300px;" alt="">
                        <div class="cropped_preview">
                        </div>
                    </div>
                </div>
                <div style="margin-top: 30px; display: flex">
                    <a href="" id="save_crop" style="width: 50%; text-align: center;"
                       class="show_preloader_on_click button">Сохранить</a>
                    <a href="" id="cancel_crop" style="margin-left: 20px; width: 50%; text-align: center;"
                       class="button button__disabled">Отменить</a>
                </div>
            </div>
        </div>

    </div>

    <div style="flex-direction: column; align-items: flex-start !important;" class="input-group">
        <div style="border: none;" class="input-file">
            <label for="work_file" id="work_file_input_label" style="padding: 0; height: 100%;"
                   class="link">
                <span id="change_avatar_button" class="">Изменить аватар</span>
                <span id="change_avatar_loader" style="display: none" class="button--loading"></span>
            </label>
            {{--                    <span wire:ignore="" id="label_work_file"><p></p></span>--}}

            <input accept=".png" style="display: none;" wire:model="avatar_file" name="work_file"
                   class="custom-file-input" id="work_file" type="file">


        </div>
        <img src="{{$avatar_cropped  ?? '/img/avatars/default_avatar.svg'}}" data-for-modal="modal_user_avatar"
             class="show_modal user_avatar" id="main_avatar" style="border-radius: 10px; width:180px;" alt="">
        <div style="display: none;" id="modal_user_avatar" class="cus-modal-container">
            <img src="{{$avatar  ?? '/img/avatars/default_avatar.svg' ?? '/img/avatars/default_avatar.svg'}}">
        </div>


    </div>

    <div class="input-group">
        <p>Имя:&nbsp</p>
        <p
            style="
            @if ($show_input === 1)
                display:none;
            @else
                display:inline;
            @endif
                "
            class="settings-text">{{$name}}</p>
        <input style="
        @if ($show_input === 1)
            display:inline;
        @else
            display:none;
        @endif
            " class="inputs" wire:model="name" type="text"
               name="name" value="{{$name}}"
               id="name">
    </div>

    <div class="input-group">
        <p>Фамилия:&nbsp</p>
        <p style="
        @if ($show_input === 1)
            display:none;
        @else
            display:inline;
        @endif
            "

           class="settings-text">{{$surname}}</p>
        <input style="
        @if ($show_input === 1)
            display:inline;
        @else
            display:none;
        @endif
            " class="inputs" wire:model="surname" type="text"
               name="surname"
               id="surname">
    </div>

    <div class="input-group">
        <p>Псевдоним:&nbsp</p>
        <p style="
        @if ($show_input === 1)
            display:none;
        @else
            display:inline;
        @endif
            "

           class="settings-text">{{$nickname}}</p>
        <input style="
        @if ($show_input === 1)
            display:inline;
        @else
            display:none;
        @endif
            " class="inputs" wire:model="nickname" type="text"
               name="nickname"
               id="nickname">
    </div>

    <div class="input-group">
        <p>Email:&nbsp</p>
        <p style="
        @if ($show_input === 1)
            display:none;
        @else
            display:inline;
        @endif
            "

           class="settings-text">{{$email}}</p>
        <input style="
        @if ($show_input === 1)
            display:inline;
        @else
            display:none;
        @endif
            " class="inputs" wire:model="email" type="text"
               name="email"
               id="email">
    </div>
    <div style="display:none;" wire:ignore class="cropped_result_block">
        {{--        <h2 style="margin: 0; font-size: 35px">Изображение:</h2>--}}
        {{--                            <div style="margin: 0 20px; display: none;" wire:loading="" wire:target="file"><p--}}
        {{--                                    style="font-size: 22px;">--}}
        {{--                                    Файл загружается</p></div>--}}
        <div id="cropped_result"></div>
    </div>


    <div class="setting-button">
        <div style="display: flex;
    flex-direction: row;
    margin-bottom: 20px;">
            <a style="box-shadow: none;
            @if ($show_input === 1)
                display:inline;
            @else
                display:none;
            @endif
                " wire:click.prevent="save()" class="header-button-wrap  button inputs">Сохранить</a>
            <a style="box-shadow: none; margin-left: 10px; color: var(--red);
            @if ($show_input === 1)
                display:inline;
            @else
                display:none;
            @endif
                " wire:click.prevent="show_0()" class="header-button-wrap  link inputs">Отменить</a>
            <a style="box-shadow: none;
            @if ($show_input === 1)
                display:none;
            @else
                display:inline;
            @endif
                " wire:click.prevent="show_1()" class="header-button-wrap  button password-reset">Редактировать</a>
        </div>

        <div style="display:flex;    flex-wrap: wrap;">
            <a style="box-shadow: none;" href="{{ route('password.request') }}"
               class="header-button-wrap  button password-reset">Восстановить пароль</a>



            <form id="logout-form" action="{{ route('logout') }}" style="
    display: flex;
 width: auto;" method="POST" class="d-none">
                @csrf
                <a style="box-shadow: none; margin-left: 10px;" class="button" href="{{ route('logout') }}"
                   onclick="event.preventDefault();
       document.getElementById('logout-form').submit();">
                    {{ __('Выйти из аккаунта') }}
                </a>
            </form>
        </div>

    </div>

    @section('page-js')
        <script src="/js/cropjs.js" crossorigin="anonymous"></script>

        <script>

            // show file loader
            $('#work_file').change(function (e) {
                $('#change_avatar_button').css('color', 'white');
                $('#change_avatar_loader').show();
                $('#work_file').attr('disabled', true);
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
                    aspectRatio: 8 / 8,
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
                        @this.emit('save_avatar')
                        }
                    });

                })

            });


        </script>
    @endsection
</div>
