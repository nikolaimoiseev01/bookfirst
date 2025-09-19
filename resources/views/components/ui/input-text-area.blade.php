@props([
    'model' => null,
    'attachable' => false,
    'description' => null
])

<div {{ $attributes->merge(['class' => 'flex flex-col w-full relative']) }}
     wire:ignore
     x-data="{
        isSending: false,
        isFocused: false,
        previews: [],
        isDropping: false,

        attachFile() {
            $refs.fileInput.click()
        },

        handleFiles(files, triggerChange = true) {
            Array.from(files).forEach(file => {
                if (file.type.startsWith('image/')) {
                    const reader = new FileReader()
                    reader.onload = e => this.previews.push({
                        type: 'image',
                        src: e.target.result,
                        name: file.name
                    })
                    reader.readAsDataURL(file)
                } else {
                    this.previews.push({ type: 'file', name: file.name })
                }
            })

            const dataTransfer = new DataTransfer()
            Array.from($refs.fileInput.files).forEach(f => dataTransfer.items.add(f))
            Array.from(files).forEach(f => dataTransfer.items.add(f))

            $refs.fileInput.files = dataTransfer.files

            // триггерим Livewire только если вызвали не из самого change
            if (triggerChange) {
                $refs.fileInput.dispatchEvent(new Event('change', { bubbles: true }))
            }
        },

                previewFiles(files) {
            this.previews = [] // если нужно перезаписывать
            Array.from(files).forEach(file => {
                if (file.type.startsWith('image/')) {
                    const reader = new FileReader()
                    reader.onload = e => this.previews.push({
                        type: 'image',
                        src: e.target.result,
                        name: file.name
                    })
                    reader.readAsDataURL(file)
                } else {
                    this.previews.push({ type: 'file', name: file.name })
                }
            })
        },

        handleDrop(files) {
            // просто кладём файлы в input и дёргаем change → Livewire подхватит
            const dataTransfer = new DataTransfer()
            Array.from(files).forEach(f => dataTransfer.items.add(f))
            $refs.fileInput.files = dataTransfer.files
            $refs.fileInput.dispatchEvent(new Event('change', { bubbles: true }))
        },

        sendMessage() {
            this.isSending = true
            $wire.send().then(() => {
                this.isSending = false
                this.previews = [] // очистим превью после отправки
                $refs.fileInput.value = null
            })
        }
    }" @dragover.prevent="isDropping = true" @dragleave.prevent="isDropping = false" @drop.prevent="
        isDropping = false;
        if ($event.dataTransfer.files.length) {
            handleFiles($event.dataTransfer.files)
        }
    ">
    <!-- Плашка drag & drop -->
    <div
        x-show="isDropping"
        class="absolute inset-0 bg-green-100/80 flex items-center justify-center text-2xl font-semibold text-green-700 z-50 rounded-xl"
    >
        Файлы сюда
    </div>

    <!-- Превью файлов -->
    <template x-if="previews.length > 0">
        <div class="flex flex-wrap gap-2 my-1 p-1 border border-dark-100 rounded">
            <template x-for="(file, index) in previews" :key="index">
                <div
                    class="relative w-24 h-16 border rounded-lg overflow-hidden flex items-center justify-center bg-gray-100">
                    <template x-if="file.type === 'image'">
                        <img :src="file.src" class="object-cover w-full h-full">
                    </template>
                    <template x-if="file.type === 'file'">
                        <span class="text-xs text-gray-700 px-1 text-center" x-text="file.name"></span>
                    </template>
                    <!-- кнопка удаления -->
                    <button type="button"
                            @click="previews.splice(index, 1)"
                            class="absolute top-0 right-0 bg-red-300 text-white text-xs rounded-bl px-1">
                        ✕
                    </button>
                </div>
            </template>
        </div>
    </template>

    <!-- Поле ввода -->
    <div class="flex w-full">
{{--        <input type="file"--}}
{{--               x-ref="fileInput"--}}
{{--               wire:model="files"--}}
{{--               x-on:livewire-upload-start="isSending = true"--}}
{{--               x-on:livewire-upload-finish="isSending = false"--}}
{{--               x-on:livewire-upload-error="isSending = false; alert('Ошибка загрузки файла')"--}}
{{--               multiple--}}
{{--               class="hidden"--}}
{{--               @change="previewFiles($event.target.files)">--}}

        <textarea
            wire:model="{{ $model }}"
            @focus="isFocused = true"
            @blur="isFocused = false"
            placeholder="{{$description}}"
            class="rounded-l-xl w-full min-h-28 text-xl outline-none ring-0 border border-r-0 border-green-500 focus:border-green-500 resize-none text-dark-400 dark:text-white bg-white dark:bg-dark_bg dark:border-gray-300 p-2"
            :class="isFocused ? 'shadow-[0_0_2px_1px_#47af984a]' : ''"
        ></textarea>

        <div
            class="flex flex-col justify-between rounded-r-xl border border-l-0 border-green-500 dark:bg-dark_bg dark:border-gray-300 p-4"
            :class="isFocused ? 'shadow-[0_0_2px_1px_#47af984a]' : ''"
        >
            @if($attachable)
                <!-- Кнопка прикрепления -->
                <x-clarity-attachment-line
                    @click="attachFile"
                    class="rotate-[-30deg] w-5 h-5 cursor-pointer fill-green-500 hover:fill-green-600 transition"
                />
            @endif

            <!-- Кнопка отправки -->
            <a x-show="!isSending" id="send-button" class="mt-auto" wire:ignore>
                <x-bi-send
                    @click="sendMessage"
                    class="mt-auto w-5 h-5 cursor-pointer fill-green-500 hover:fill-green-600 transition hover:scale-110"
                />
            </a>

            <!-- Спиннер -->
            <div x-show="isSending" id="send-button__spinner" wire:ignore>
                <x-ui.spinner class="w-5 h-5 mt-auto"/>
            </div>
        </div>
    </div>
</div>
