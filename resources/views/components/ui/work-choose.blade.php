<div
    wire:ignore
    x-data="{
        search: '',
        showWorks: false,
        userWorks: @js($userWorks),
        selectedWorks: $wire.entangle('selectedWorks').live,
        dragIndex: null,
        dragOverIndex: null,


        filteredWorks() {
            let q = this.search.toLowerCase()
            return this.userWorks.filter(w =>
                w.title.toLowerCase().includes(q)
            )
        },

        selectWork(work) {
            this.selectedWorks.push(work)
            const i = this.userWorks.findIndex(w => w.id === work.id)
            if (i !== -1) this.userWorks.splice(i, 1)
        },

        removeWork(work) {
            this.userWorks.push(work)
            const i = this.selectedWorks.findIndex(w => w.id === work.id)
            if (i !== -1) this.selectedWorks.splice(i, 1)
        },

        startDrag(index, e) {
            this.dragIndex = index
            e.dataTransfer.effectAllowed = 'move'
        },

        onDragOver(index, e) {
            if (this.dragIndex !== null && this.dragIndex !== index) {
                this.dragOverIndex = index
            }
        },

        onDrop(index) {
            if (this.dragIndex === null || this.dragIndex === index) return

            const moved = this.selectedWorks.splice(this.dragIndex, 1)[0]
            this.selectedWorks.splice(index, 0, moved)

            this.dragIndex = null
            this.dragOverIndex = null
        },

        endDrag() {
            this.dragIndex = null
            this.dragOverIndex = null
        }
    }"
    {{ $attributes->merge(['class' => 'relative flex gap-6 w-full p-4 border border-green-500 rounded-2xl']) }}
>
    <script>
        function workSelector() {
            return {}
        }
    </script>

    <div class="relative z-30 flex items-center" @click.outside="showWorks = false">
        <div class="flex flex-col items-center cursor-pointer transition hover:scale-105" @click="showWorks = !showWorks">
            <span
                class="text-5xl font-light flex justify-center items-center p-2 border-2 border-green-400 rounded-full aspect-square w-12 h-12 text-green-400">
                +
            </span>
            <span class="text-green-400 text-2xl">Добавить</span>
        </div>

        <div x-show="showWorks"
             x-transition
             class="absolute top-full left-1/2 -translate-x-1/2 flex flex-col gap-2 border border-green-400 rounded-2xl p-4 bg-white min-w-2xs">
            <p class="font-normal text-nowrap text-xl">Мои произведения:</p>

            <div x-show="filteredWorks().length > 0">
                <x-ui.input.text x-model="search" class="!text-lg !py-0" placeholder="поиск"/>
            </div>

            <div x-show="filteredWorks().length > 0" class="max-h-52 overflow-y-auto p-2 flex flex-col gap-2">
                <!-- Список -->
                <template x-for="work in filteredWorks()" :key="work.id">
                    <div
                        class="container !rounded flex justify-between items-center py-2 px-3 hover:bg-gray-100 cursor-pointer hover:scale-[102%] transition"
                        @click="selectWork(work)"
                    >
                        <span class="text-dark-400"
                              x-text="work.title.length > 30 ? work.title.slice(0, 30) + '…' : work.title"></span>
                        <x-bi-chevron-double-right class="fill-green-500 w-5 h-auto"/>
                    </div>
                </template>
            </div>

            <!-- Пусто -->
            <div x-show="filteredWorks().length === 0" class="text-gray-400 text-lg">
                У Вас еще нет произведений! Для того, чтобы учавствовать в сборниках, произведения должны сначала быть
                добавлены в нашу систему, а затем выбраны из этого списка.
            </div>

            <!-- Кнопки -->
            <div class="flex flex-col gap-1 text-sm text-blue-600">
                <x-ui.link-simple href="{{route('account.works.create.manual')}}"
                                  class="flex items-center !font-normal gap-1 text-xl !text-green-400 text-nowrap">
                    <span class="text-2xl">+</span> Добавить вручную
                </x-ui.link-simple>
                <x-ui.link-simple href="{{route('account.works.create.file')}}"
                                  class="flex items-center !font-normal gap-1 text-xl !text-green-400 text-nowrap">
                    <x-bi-file-earmark-arrow-up/>
                    Добавить файлом
                </x-ui.link-simple>
            </div>
        </div>
    </div>

    <!-- Правая колонка -->
    <div class="flex flex-wrap gap-4 items-start flex-1">
        <template x-for="(work, index) in selectedWorks" :key="work.id">
            <div
                class="cursor-grab active:cursor-grabbing transition relative w-[150px] min-h-[42px] max-h-[42px] h-[42px]"
                :class="{'opacity-50 scale-95': dragIndex === index}"
                draggable="true"
                @dragstart="startDrag(index, $event)"
                @dragover.prevent="onDragOver(index, $event)"
                @drop="onDrop(index)"
                @dragend="endDrag"
            >
                <!-- Плейсхолдер -->
                <div
                    x-show="dragOverIndex === index && dragIndex !== index"
                    class="absolute inset-0 border-2 border-dashed border-green-400 rounded-lg transition-all duration-200 ease-in-out"
                ></div>

                <!-- Карточка -->
                <div
                    class="relative container rounded flex justify-between items-center py-2 px-3 bg-white border border-gray-200 shadow-sm cursor-grab active:cursor-grabbing transition-transform duration-200 ease-in-out"
                    :class="{
                        'opacity-50 scale-95': dragIndex === index,
                        'hover:scale-[102%]': dragIndex !== index
                    }"
                >
                    <span class="text-dark-400 block truncate"
                          x-text="work.title.length > 30 ? work.title.slice(0, 30) + '…' : work.title"></span>
                    <x-ui.tooltip-wrap text="Убрать" class="!cursor-pointer" @click="removeWork(work)">
                        <x-bi-x class="text-dark-400 w-5 h-auto"/>
                    </x-ui.tooltip-wrap>
                </div>
            </div>
        </template>
    </div>

    <x-ui.question-mark class="!absolute bottom-4 right-4">
        Произведения можно перемещать для изменения порядка
    </x-ui.question-mark>
</div>
