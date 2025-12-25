<div
    x-data="{ open: false, hoveredType: null }"
    class="relative w-72"
    wire:ignore
>
    <!-- Кнопка -->
    <a
        @click="open = !open"
        class="w-full block mt-4 rounded-lg border px-4 py-2 text-left hover:bg-gray-100"
    >
        Шаблоны сообщений
    </a>

    <!-- Выпадающий список типов -->
    <div
        x-show="open"
        x-transition
        @click.outside="open = false" style="bottom: 100%;"
        class="absolute left-0 bottom-[100%] mt-2 w-full rounded-lg border bg-white shadow-lg z-50 "
    >
        @foreach ($templates as $type => $messages)
            <div
                class="relative"
                @mouseover="hoveredType = '{{ $type }}'"
                @mouseleave="hoveredType = null"
            >
                <!-- Тип -->
                <div class="px-4 py-2 cursor-pointer hover:bg-gray-50 font-medium">
                    {{ $type }}
                </div>

                <!-- Сообщения -->
                <div
                    x-show="hoveredType === '{{ $type }}'"
                    x-transition
                    class="absolute left-full ml-2 w-80 rounded-lg border bg-white shadow-xl"
                >
                    @foreach ($messages as $message)
                        <span
                            @click="
                                $dispatch('select-template', { text: '...' });
                                open = false
                            "
                            class="block w-full text-left px-4 py-2 hover:bg-green-50"
                        >
                            {{ $message['id'] }}
                        </span>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
</div>
