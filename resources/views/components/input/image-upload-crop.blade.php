<div wire:ignore class="crop_image_block_wrap">
    <label for="work_file" id="work_file_input_label" class="button">
        Добавить изображение
    </label>

    <input accept=".png, .jpg" wire:model="file" name="work_file"
           class="custom-file-input" id="work_file" type="file">


    <div class="cropped_result_block_wrap">
        <div class="cropped_result_wrap">
            <p>Добавленное изображение:</p>
            <img src="{{$cropped}}" alt="">
            <div id="cropped_result">

            </div>
            <a id="delete_pic_preview" class="link">Удалить</a>
        </div>
    </div>
</div>

@push('page-js')
    <script>

        // show file loader


        // Если уже есть картинка - показываем ее в отдельном блоке
        default_pic_el = $('.cropped_result_block img')
        if ("{{$cropped}}") {
            default_pic_el.show()
            $('.cropped_result_block_wrap').show();
            $('#work_file_input_label').addClass('disabled')
            $('#work_file').attr("disabled", "disabled")

        }

        // Отдельный слушатель, чтобы вызвать без Livewire
        $('#delete_pic_preview').click(function () {
            $('.cropped_result_block_wrap').hide();
            $('#work_file_input_label').removeClass('disabled')
            $("#work_file").removeAttr("disabled");
            $('.cropped_result_wrap img').hide();
            @this.set("filepreview", null);
            @this.set("file", null);
            @this.set("cropped_img", null);
        })

        document.addEventListener('update_preview', function () {

            // Показываем модалку
            $('#crop_preview').fadeIn();

            const concernedElement = document.querySelector(".modal-container");
            document.addEventListener("mousedown", (event) => {
                if (!concernedElement.contains(event.target)) {
                    $('#crop_preview').fadeOut();
                }
            });

            const image = document.getElementById('work_img_preview');

            var cropper = new Cropper(image, {
                aspectRatio: 16 / 8.5,
                viewMode: 2,
                dragMode: 'move',
                preview: '.cropped_preview',
                ready: function () {
                    cropper_el = this.cropper
                    $('#save_crop').click(function (e) {
                        e.preventDefault()
                        croppedCanvas = cropper_el.getCroppedCanvas()
                        e.preventDefault()
                        $('.cropped_result_block_wrap').show();


                        $('#work_file_input_label').addClass('disabled')
                        $('#work_file').attr("disabled", "disabled")

                        $('#crop_preview').fadeOut();

                        // Crop
                        // croppedCanvas = cropper.getCroppedCanvas();
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

                    $('#delete_pic_preview').click(function () {
                        cropper_el.destroy();
                    })


                    $('#cancel_crop').click(function (e) {
                        e.preventDefault()
                        cropper_el.destroy();
                    @this.set("filepreview", '');

                        $('#crop_preview').fadeOut();
                    })

                }


            });
        });
    </script>
@endpush
