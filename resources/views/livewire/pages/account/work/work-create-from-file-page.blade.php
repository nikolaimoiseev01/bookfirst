<div class="mb-16">
    @section('title')
        Добавление произведения из файла
    @endsection
    <div class="flex flex-col gap-4" x-data="{fileWorks: @entangle('fileWorks')}">
        <div class="flex gap-2 items-center">
            <h3>Правила: </h3>
            <x-ui.link>Подробная инструкция</x-ui.link>
        </div>
        <ul class="list-disc pl-6">
            <li>Файл должен содержать только произведения</li>
            <li>Файл должен быть строго формата .docx</li>
            <li>Не нужно начинать каждое произведение с новой страницы</li>
            <li>Название должно быть выделено жирным</li>
            <li>Текст нежирный (внутри текста нельзя вставлять жирные символы)</li>
        </ul>
        <x-ui.link-simple :isLivewire="false" download="Пример загрузки из файла.docx"
                          href="/fixed/public_documents/add_work_from_doc_example.docx">Скачать
            пример файла
        </x-ui.link-simple>
        <p x-show="!fileWorks">Если все правила учтены, мы готовы анализировать Ваш файл:</p>
        <div class="flex flex-col max-w-xl" x-show="!fileWorks">
            <x-filepond::upload wire:model="file"/>
            <x-ui.button wire:click="scan()">Распознать</x-ui.button>
        </div>
        <div x-show="fileWorks" class="flex flex-col max-w-7xl">
            <div class="flex gap-4 mb-4 items-center">
                <h3>Распознанные произведения:</h3>
                <x-ui.button wire:click="confirmSaveAllWorks()">Загрузить в систему</x-ui.button>
            </div>

            <div class="grid grid-cols-2 gap-8">
                <template x-for="(work, index) in fileWorks" :key="index">
                    <div x-data="{editable: false, title: work.title, text: work.text}"
                         :class="editable ? 'col-span-2 min-h-[500px]' : ''"
                         class="flex flex-col container p-4">
                        <div class="flex gap-2 items-center justify-between mb-4">
                            <p x-show="!editable" class="font-medium truncate "
                               x-text="work.title"></p>
                            <x-ui.input.text x-model="work.title" x-show="editable"/>
                            <div class="flex gap-2">
                                <div x-show="!editable">
                                    <x-ui.tooltip-wrap text="Редактировать">
                                        <x-bi-pencil @click="editable = true"
                                                     class="w-7 fill-green-500 cursor-pointer"/>
                                    </x-ui.tooltip-wrap>
                                </div>

                                <x-ui.tooltip-wrap x-show="editable" text="Сохранить">
                                    <x-bi-check @click="editable = false"
                                                class="w-10 h-auto text-green-500 cursor-pointer"/>
                                </x-ui.tooltip-wrap>

                                <div  x-show="!editable">
                                    <x-ui.tooltip-wrap  text="Удалить">
                                        <x-bi-trash @click="fileWorks.splice(index, 1)"
                                                    class="w-5 h-auto text-red-300 cursor-pointer"/>
                                    </x-ui.tooltip-wrap>
                                </div>
                            </div>
                        </div>
                        <p x-show="!editable" x-text="work.text"></p>
                        <x-ui.input.text-area class="flex-1" x-model="work.text" x-show="editable"
                                              :sendable="false" :attachable="false"/>
                    </div>
                </template>
            </div>
        </div>
    </div>
</div>

