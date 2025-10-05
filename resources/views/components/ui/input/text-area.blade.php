<div {{ $attributes->merge(['class' => 'textAreaCustom flex flex-col w-full relative']) }}
     wire:ignore
     x-data="{
        isSending:  $wire.entangle('isSending'),
        isFocused: false,
        isDropping: false,
      leaveTimeout: null,
        browseFileTrigger() {
             $el.closest('[x-data]').querySelector('.filepond--drop-label')?.click()
        },
        handleDrop(e) {
            e.preventDefault();
            e.stopPropagation();
            if (window.__pond && e.dataTransfer.files.length) {
                window.__pond.addFiles(e.dataTransfer.files);
            }
        },
        sendMessage() {
            this.isSending = true;
            this.$wire.sendMessage()
        }
        }"
     @dragleave.prevent="
        leaveTimeout = setTimeout(() => {
            isDropping = false
        }, 500) // задержка 200мс
    "
     @dragover.prevent="
        clearTimeout(leaveTimeout);
        isDropping = true
    "

     @filepond-upload-started.window="isSending=true; disableSendButtons(true)"
     @filepond-upload-completed.window="isSending=false; disableSendButtons(false)"
     @filepond-upload-reverted.window="isSending=false; disableSendButtons(false)"
     @filepond-upload-reset.window="isSending=false; disableSendButtons(false)"
     @filepond-upload-done.window="isSending=false; disableSendButtons(false)"
     @filepond-upload-aborted.window="isSending=false; disableSendButtons(false)"

     x-on:update-is-sending.window="console.log('FINE')"
>
    @if($attachable)
        <x-ui.chat-file-upload wire:model="{{ $filesModel }}" :multiple="$multiple"/>
    @endif

    <!-- Поле ввода -->
    <div class="flex w-full flex-1 transition rounded-2xl border border-{{$color}}  p-2"
         :class="isFocused ? 'shadow-[0_0_2px_1px_#47af984a]' : ''">

        <textarea
            wire:model="{{ $textModel }}"
            @focus="isFocused = true"
            @blur="isFocused = false"
            placeholder="{{$description}}"
            class="rounded-l-xl w-full min-h-28 text-xl outline-none ring-0 resize-none text-dark-400 dark:text-white bg-white dark:bg-dark_bg dark:border-gray-300"
        ></textarea>

        <div
            class="flex flex-col justify-between rounded-r-xl dark:bg-dark_bg dark:border-gray-300 p-2"
        >
            @if($attachable)
                <x-ui.tooltip-wrap text="Прикрепить файлы <br>(или перенесите файлы в поле текста)">
                    <x-clarity-attachment-line
                        @click="browseFileTrigger()"
                        class="rotate-[-30deg] w-5 h-5 cursor-pointer fill-{{$color}} hover:scale-110 transition"
                    />
                </x-ui.tooltip-wrap>
            @endif

            @if($sendable)
                <a x-show="!isSending" id="send-button" class="mt-auto" wire:ignore>
                    <x-ui.tooltip-wrap text="Отправить">
                        <x-bi-send
                            @click="sendMessage"
                            class="mt-auto w-5 h-5 cursor-pointer fill-{{$color}} transition hover:scale-110"
                        />
                    </x-ui.tooltip-wrap>
                </a>

                <div x-show="isSending" id="send-button__spinner" wire:ignore>
                    <x-ui.spinner class="w-5 h-5 mt-auto"/>
                </div>
            @endif
        </div>
    </div>
</div>
