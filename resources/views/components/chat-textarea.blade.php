<div class="input_wrap">
    @if($attachable)
        <div wire:ignore class="chat_files">
            <input accept multiple name="chat_files" class="chat_filepond"
                   type="file"/>
        </div>
    @endif

    <div wire:ignore class="input_block">
                <textarea oninput="auto_grow(this)"
                          class="textarea_chat"
                          wire:model="{{$model}}"
                          placeholder="{{$placeholder}}"
                          type="text"
                ></textarea>

        <div class="buttons_wrap">

            @if($attachable)
                <span class="tooltip" title="Прикрепить файл">
                                            <svg onclick="trigger_filepond_function()" class="attach_icon"
                                                 viewBox="0 0 268.12 494.4">
                        <path
                            d="M247.2,0C173.29,0,113.14,60.13,113.14,134.06V387.87a16.39,16.39,0,1,0,32.78,0V134.06a101.28,101.28,0,0,1,202.56,0V395.73a66,66,0,0,1-65.89,65.89c-.27,0-.52.14-.79.16s-.51-.16-.79-.16a66,66,0,0,1-65.9-65.89v-157a32.09,32.09,0,1,1,64.18,0v149.1a16.39,16.39,0,0,0,32.78,0V238.77a64.87,64.87,0,1,0-129.74,0v157A98.78,98.78,0,0,0,281,494.4c.29,0,.52-.15.8-.16s.52.16.79.16a98.79,98.79,0,0,0,98.67-98.67V134.06C381.26,60.13,321.11,0,247.2,0Z"
                            transform="translate(-113.14 0)"/>
                    </svg>
                    </span>
            @endif

            @if($sendable)
                <a wire:click.prevent="new_message()" class="show_preloader_on_click link send_button tooltip log_check"
                   title="Отправить">
                    <svg id="send_message_{{$chat['id'] ?? 999999}}" id="Capa_1" data-name="Capa 1"
                         xmlns="http://www.w3.org/2000/svg"
                         viewBox="0 0 512 512">
                        <path
                            d="M507.61,4.39a15,15,0,0,0-16.18-3.32l-482,192.8a15,15,0,0,0-1,27.43l190.07,92.18L290.7,503.54A15,15,0,0,0,304.2,512h.53a15,15,0,0,0,13.4-9.42l192.8-482A15,15,0,0,0,507.61,4.39ZM52.09,209.12l382.63-153-228,228ZM302.88,459.91l-75-154.6,228-228Z"
                            transform="translate(0 0)"/>
                    </svg>
                </a>
            @endif
        </div>
    </div>

    @push('page-js')
        @once
            <script>
                function trigger_filepond_function() { /* Чтобы при клике на иконку, тригерился запуск FilePond плагина */
                    $('#file_pond_button').click();
                }

                function auto_grow(element, start_height) {
                    // $(element).closest('.input-block').css('height', start_height + "px");
                    $(element).closest('.input_block').css('height', 100 + "px");
                    final_height = element.scrollHeight;
                    $(element).closest('.input_block').css('height', final_height + 2 + "px");
                };

                $('.send_button').on('click', function () {
                    $(this).closest('.input_block').removeAttr('wire:ignore');
                })

                function make_chat_filepond() {  /* Работа с загрузкой файлов */

                    function calculate_after_update() { // Задаем переменную livewire
                        wire_id = $('.chat_wrap').attr('wire:id') // Ищем wire:id, потому что
                        message_files = [] // он меняется при переключении чатов
                        $("[name='chat_files']").each(function () {
                            if ($(this).val() !== '') {
                                message_files.push($(this).val())
                            }
                        })
                        window.livewire.find(wire_id).set('message_files', message_files);
                    }

                    function control_send_button(enable) {
                        if (enable === 1) {
                            $('.send_button').attr('wire:click.prevent', 'new_message()')
                            $('.send_button').removeClass('disabled')
                        } else {
                            $('.send_button').attr('wire:click.prevent', '')
                            $('.send_button').addClass('disabled')
                        }
                    }


                    $('.chat_filepond').filepond({
                        server: {
                            url: '/myaccount/temp-uploads/chat_files',
                            headers: {
                                'X-CSRF-TOKEN': '{{csrf_token()}}'
                            }
                        },
                        onprocessfile: () => {
                            control_send_button(1)
                            calculate_after_update();
                        },
                        onremovefile: () => {
                            calculate_after_update();
                        },

                        onaddfilestart: (file) => {
                            control_send_button(0)
                        },

                        labelIdle: `<span id="file_pond_button"></span>`,
                        maxFileSize: '40MB',
                        maxTotalFileSize: '40MB',
                        labelMaxFileSizeExceeded: 'Размер превышен!',
                        labelMaxFileSize: 'Максимальный: {filesize}',
                        labelMaxTotalFileSizeExceeded: 'Сумма размеров превышена!',
                        labelMaxTotalFileSize: 'Максимум: {filesize}',
                    });
                }

                make_chat_filepond()
            </script>
        @endonce
    @endpush

</div>
