<div style="    width: 100%;">
    <link rel="stylesheet" href="/css/chat.css">
    <form
        wire:submit.prevent=""
        enctype="multipart/form-data">
        @csrf
        <div class="messages">
            @if(count($messages) == 0)
                <div style="margin:0; height: 100%; display: flex; align-items: center; justify-content: center"
                     class="no-access">
                            <span>
                                Это чат с Вашим личным менеджером по конкретно этому изданию.
                                В нем пока нет сообщений.</br>
                                Здесь Вы можете задать любые вопросы, а также прикреплять файлы при необходимости.
                            </span>
                </div>
            @endif
            @foreach($messages as $message)
                <div class="message">
                    <p style="font-size: 18px;">@if($message['user_from'] === 2)
                            Поддержка @else {{App\Models\User::where('id',$message['user_from'])->value('name')}}@endif</p>
                    <div style="background:
                    @if($message['user_from'] === Auth::user()->id)
                        #47AF98;
                    @else #e7b34d
                    @endif" class="message-wrap">
                        <p>{{$message['text']}}</p>
                        @if (count($message->message_file) > 0)
                            <div>
                                <h2 style="font-size: 24px; margin-bottom: 0; margin-top: 10px;">Прикрепленные
                                    файлы:</h2>
                                <div style="display: flex; flex-direction: column;">
                                    @foreach($message->message_file as $message_file)
                                        <p><a download href="/{{$message_file['file']}}">
                                                <svg width="15px" style="fill:#ffffff" viewBox="0 0 480 512">
                                                    <path
                                                        d="M382.56,233.38A16,16,0,0,0,368,224H304V16A16,16,0,0,0,288,0H224a16,16,0,0,0-16,16V224H144a16,16,0,0,0-12,26.53l112,128a16,16,0,0,0,24.06,0l112-128A16,16,0,0,0,382.56,233.38Z"
                                                        transform="translate(-16 0)"/>
                                                    <path
                                                        d="M432,352v96H80V352H16V480a32,32,0,0,0,32,32H464a32,32,0,0,0,32-32V352Z"
                                                        transform="translate(-16 0)"/>
                                                </svg>
                                            </a>{{substr($message_file['file'], strrpos($message_file['file'], '/') + 1)}}
                                        </p>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                    </div>
                    <p style="font-size: 17px; float:right;">{{\Illuminate\Support\Str::substr($message['created_at'],0,16)}}</p>
                </div>
            @endforeach
        </div>

        @if($chat['chat_status_id'] <> 3)
            <div class="chat_files" wire:ignore>
                <input wire:ignore accept multiple name="chat_files" class="chat_filepond" type="file"/>
            </div>
            <div class="input-block">
                <textarea class="textarea_chat"
                          wire:model="text"
                          style="border-radius: 10px 0 0 10px; border-right: none;" name="chat_text" required
                          type="text"></textarea>
                <div class="send-wrap">
                    <span style="display: grid; position: absolute; right: 28px;">
                        <span class="tooltip" title="Прикрепить файл">
                        <svg onclick="trigger_filepond_function()" class="attach_icon" viewBox="0 0 268.12 494.4"><path
                                d="M247.2,0C173.29,0,113.14,60.13,113.14,134.06V387.87a16.39,16.39,0,1,0,32.78,0V134.06a101.28,101.28,0,0,1,202.56,0V395.73a66,66,0,0,1-65.89,65.89c-.27,0-.52.14-.79.16s-.51-.16-.79-.16a66,66,0,0,1-65.9-65.89v-157a32.09,32.09,0,1,1,64.18,0v149.1a16.39,16.39,0,0,0,32.78,0V238.77a64.87,64.87,0,1,0-129.74,0v157A98.78,98.78,0,0,0,281,494.4c.29,0,.52-.15.8-.16s.52.16.79.16a98.79,98.79,0,0,0,98.67-98.67V134.06C381.26,60.13,321.11,0,247.2,0Z"
                                transform="translate(-113.14 0)"/></svg>
                        </span>
                    </span>
                    <button id="send_message_{{$chat['id']}}" style="height: 75px;" type="submit">
                        <div style="position: relative;" class="send_mes_button">
                            <span id="send_env" class="tooltip" title="Отправить">
                                <svg id="Capa_1" data-name="Capa 1"
                                     xmlns="http://www.w3.org/2000/svg"
                                     viewBox="0 0 512 512">
                                    <path
                                        d="M507.61,4.39a15,15,0,0,0-16.18-3.32l-482,192.8a15,15,0,0,0-1,27.43l190.07,92.18L290.7,503.54A15,15,0,0,0,304.2,512h.53a15,15,0,0,0,13.4-9.42l192.8-482A15,15,0,0,0,507.61,4.39ZM52.09,209.12l382.63-153-228,228ZM302.88,459.91l-75-154.6,228-228Z"
                                        transform="translate(0 0)"/>
                                </svg>
                            </span>
                            <span style="display: none;" id="send_preloader" class="button--loading"></span>
                        </div>
                    </button>
                </div>
            </div>
        @else
            <div class="chat-closed">
                <p style="margin-right:20px;">Этот чат закрыт</p>
                <a class="button" wire:click.prevent="reopenChat({{$chat['id']}})">
                    Открыть чат заного
                </a>
            </div>
        @endif
    </form>

    @section('page-js')

        <script>
            $('.send_mes_button').on('click', function () {
                $('#send_preloader').show();
                $('#send_env').css('opacity', 0);
                $('.send-wrap button').prop("disabled",true);
            })
        </script>
        <script>

            function when_upload_start() {
                $('#send_message_{{$chat['id']}}').prop('disabled', true);
            }

            // --- Работа с загрузкой файлов
            function calculate_after_upload() {
                var totalFiles = $('.chat_files .upload_type .filepond--item').length;
                var completedFiles = $('.chat_files .upload_type .filepond--item[data-filepond-item-state="processing-complete"]').length;
                if (completedFiles === totalFiles) {
                    message_files_paths = $("[name='chat_files']");
                    message_files = '';
                    for (var i = 0; i < message_files_paths.length; i++) {
                        if ($(message_files_paths[i]).val() != '') {
                            message_files += 'filepond_temp/' + $(message_files_paths[i]).val() + ';';
                        }
                    }
                @this.set("message_files", message_files.slice(0, -1));
                } else {

                }
            }

            function calculate_after_remove() {
                message_files_paths = $("[name='chat_files']");
                message_files = '';
                for (var i = 0; i < message_files_paths.length; i++) {
                    if ($(message_files_paths[i]).val() != '') {
                        message_files += $(message_files_paths[i]).val() + ';';
                    }
                }
            @this.emit('message_files', message_files.slice(0, -1));

            }

            FilePond.registerPlugin(FilePondPluginFileValidateSize);

            $('.chat_filepond').filepond({
                server: {
                    url: '/myaccount/temp-uploads/chat_files',
                    headers: {
                        'X-CSRF-TOKEN': '{{csrf_token()}}'
                    }
                },
                onprocessfile: (file) => {
                    calculate_after_upload();
                },
                onaddfilestart: (file) => {
                    when_upload_start();
                },
                onremovefile: (file) => {
                    calculate_after_remove();
                },
                labelIdle: `<span id="file_pond_button"></span>`,
                maxFileSize: '10MB',
                maxTotalFileSize: '20MB',
                labelMaxFileSizeExceeded: 'Размер превышен!',
                labelMaxFileSize: 'Максимальный: {filesize}',
                labelMaxTotalFileSizeExceeded: 'Сумма размеров превышена!',
                labelMaxTotalFileSize: 'Максимум: {filesize}',
            });

            var filePondObj = FilePond.create($('.chat_filepond').input);


            function trigger_filepond_function() {
                $('#file_pond_button').click();
            }

            document.addEventListener('clear_filepond', function () {
                $('.chat_filepond').filepond('removeFiles')
            });

            // --- // Работа с загрузкой файлов
        </script>

        <script>
            document.addEventListener('livewire:load', function () {
                $("#send_message_{{$chat['id']}}").click(function (event) {
                    event.preventDefault();
                    Livewire.emit('new_message')
                })
            })
        </script>

    @endsection
</div>
