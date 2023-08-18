<div x-data class="settings_account_page_wrap">
    @section('page-style')
        <link rel="stylesheet" href="/css/cropjs.css" crossorigin="anonymous"/>
    @endsection


    <div id="crop_preview" class="modal">
        <div class="modal-wrap">
            <div class="modal-container">
                <h1 class="header">Выберите миниатюру</h1>
                <div class="images_wrap">
                    <img src="{{$avatar_file_preview}}" id="work_img_preview" alt="">
                    <div class="cropped_preview"></div>
                </div>
                <div class="buttons_wrap">
                    <a id="save_crop" class="button show_preloader_on_click">Сохранить</a>
                    <a id="cancel_crop" class="button grey">Отменить</a>
                </div>
            </div>
        </div>
    </div>

    <div class="general_wrap">
        <div  x-show="!$wire.show_input" class="info_wrap">
            <p>Имя: {{$name}}</p>
            <p>Фамилия: {{$surname}}</p>
            <p>Псевдоним: {{$nickname}}</p>
            <p>Email: {{$email}}</p>
            <a @click="$wire.show_input = true" x-show="!$wire.show_input"
               class="link">Редактировать</a>
        </div>

        <div x-cloak
             x-show="$wire.show_input" class="form_wrap">
            <div class="input-group">
                <p>Имя</p>
                <input class="inputs" wire:model="name" type="text"
                       name="name" value="{{$name}}"
                       id="name">
            </div>


            <div class="input-group">
                <p>Фамилия</p>
                <input class="inputs" wire:model="surname" type="text"
                       name="surname"
                       id="surname">
            </div>
            <div class="input-group">
                <p>Псевдоним</p>
                <input class="inputs" wire:model="nickname" type="text"
                       name="nickname"
                       id="nickname">
            </div>
            <div class="input-group">
                <p>Email</p>
                <input class="inputs" wire:model="email" type="text"
                       name="email"
                       id="email">
            </div>
            <a wire:click.prevent="save()" class="header-button-wrap  button inputs">Сохранить</a>
            <a @click="$wire.show_input = false" class="header-button-wrap  link inputs">Отменить</a>
        </div>

        <div class="avatar_wrap">
            <div class="input-file">
                <label for="work_file" id="work_file_input_label" class="link">
                    <span id="change_avatar_button">Изменить аватар</span>
                </label>

                <input accept=".png" style="display: none;" wire:model="avatar_file" name="work_file"
                       class="custom-file-input" id="work_file" type="file">
            </div>

            <img src="{{$avatar_cropped  ?? '/img/avatars/default_avatar.svg'}}" data-for-modal="modal_user_avatar"
                 class="show_modal user_avatar" id="main_avatar" alt="">
            <div style="display: none;" id="modal_user_avatar" class="cus-modal-container">
                <img src="{{$avatar  ?? '/img/avatars/default_avatar.svg' ?? '/img/avatars/default_avatar.svg'}}">
            </div>

            <div style="display:none;" wire:ignore class="cropped_result_block">
                <div id="cropped_result"></div>
            </div>
        </div>
    </div>


    <div class="settings_buttons_wrap">



        <a href="{{ route('password.request') }}"
           class="header-button-wrap  button password-reset">Восстановить пароль</a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
            @csrf
            <a class="button" href="{{ route('logout') }}"
               onclick="event.preventDefault();
       document.getElementById('logout-form').submit();">
                {{ __('Выйти из аккаунта') }}
            </a>
        </form>


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
