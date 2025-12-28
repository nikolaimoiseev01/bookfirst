<div
    x-data="messageTemplatesDropdown()"
    class="relative w-fit mt-auto"
    wire:ignore
>
    <script type="application/json" x-ref="templates">
        @json($templates, JSON_UNESCAPED_UNICODE)
    </script>
    <!-- Кнопка -->
    <div @click="open = !open">
        <x-bi-question-circle class="text-green-500 w-5 h-auto cursor-pointer"/>
    </div>

    <!-- Выпадающее окно -->
    <div
        x-show="open"
        x-transition
        @click.outside="
            open = false;
            selectedType = null;
            hideTooltip();
        "
        class="absolute mt-2 rounded-lg border bg-white dark:bg-dark_bg shadow-lg z-50"
        style="width: 250px; overflow: hidden; height: 265px; right: 100%; bottom: 100%;"
    >
        <!-- ===== ТИПЫ ===== -->
        <div x-show="!selectedType">
            <template x-for="(messages, type) in templates" :key="type">
                <div
                    @click="selectedType = type"
                    class="px-4 py-2 cursor-pointer hover:bg-gray-50 dark:hover:!bg-dark-500 font-medium"
                >
                    <span x-text="type"></span>
                </div>
            </template>
        </div>

        <!-- ===== СООБЩЕНИЯ ===== -->
        <div x-show="selectedType" class="flex flex-col h-full">
            <!-- Назад -->
            <div
                @click="selectedType = null; hideTooltip()"
                class="px-4 py-2 text-sm dark:text-white text-gray-500 cursor-pointer hover:bg-gray-50 dark:hover:!bg-dark-500"
            >
                ← Назад
            </div>

            <div class="border-t"></div>

            <!-- Список сообщений -->
            <div class="overflow-y-auto flex-1">
                <template x-for="message in templates[selectedType]" :key="message.id">
                    <div
                        @mouseenter="showTooltip($event, message.text)"
                        @mouseleave="hideTooltip()"
                        @click="
            hideTooltip();
            $dispatch('selectMessageTemplate', { text: message.text ?? '...' });
            open = false;
            selectedType = null;
        "
                        class="                        px-4 py-2
                        cursor-pointer
                        hover:bg-gray-50
                        dark:hover:!bg-dark-500
                        font-medium
                        truncate
                        whitespace-nowrap
                    "

                        x-text="message.title"
                    ></div>
                </template>

            </div>
        </div>

        <!-- ===== TOOLTIP ===== -->
        <div
            x-show="tooltip.visible"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 scale-95 translate-y-1"
            x-transition:enter-end="opacity-100 scale-100 translate-y-0"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            :style="`
        top: ${tooltip.y + 12}px;
        right: ${window.innerWidth - tooltip.x + 12}px;
    `"
            class="fixed z-[9999] max-w-xs px-3 py-2 text-sm text-black dark:bg-dark_bg dark:text-white bg-white max-w-xl rounded-lg shadow-lg pointer-events-none"
        >
            <span x-text="tooltip.text"></span>
        </div>



    </div>
</div>


<script>
    function messageTemplatesDropdown() {
        return {
            open: false,
            selectedType: null,

            templates: null,
            types: [],

            tooltip: {
                visible: false,
                text: '',
                x: 0,
                y: 0,
                timer: null,
            },

            init() {
                // ⚠️ читаем JSON как обычные данные
                this.templates = JSON.parse(
                    this.$refs.templates.textContent
                )

                this.types = Object.keys(this.templates)
            },

            showTooltip(e, text) {
                if (this.tooltip.timer || this.tooltip.visible) return

                this.tooltip.timer = setTimeout(() => {
                    this.tooltip.text = text
                    this.tooltip.x = e.clientX
                    this.tooltip.y = e.clientY
                    this.tooltip.visible = true
                    this.tooltip.timer = null
                }, 500)
            },

            hideTooltip() {
                clearTimeout(this.tooltip.timer)
                this.tooltip.timer = null
                this.tooltip.visible = false
            },
        }
    }
</script>
