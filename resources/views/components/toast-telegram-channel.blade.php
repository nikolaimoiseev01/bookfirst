<div class="fixed bottom-4 left-4 z-[9999] flex w-[calc(100%-2rem)] max-w-lg flex-col gap-3 overflow-visible">
    <x-ui.news-toast inline storageKey="referral_toast_closed_v1" delay="2000" pulseDelay="0" class="w-full">
        <div x-data="{
            expanded: false,
            clamp: true,
            clampTimer: null,
            toggle() {
                clearTimeout(this.clampTimer);
                if (this.expanded) {
                    this.expanded = false;
                    this.clampTimer = setTimeout(() => this.clamp = true, 500);
                    return;
                }
                this.clamp = false;
                this.expanded = true;
            }
        }" class="flex flex-col gap-3">
            <div class="flex items-start justify-between gap-3">
                <h4 class="whitespace-nowrap text-xl font-semibold">📚 Присоединяйтесь к нашему Telegram-каналу!</h4>
                <div class="group relative shrink-0">
                    <button type="button" @click="close()" aria-label="Закрыть и больше не показывать"
                            class="rounded-full p-1 text-gray-500 transition hover:bg-black/5 hover:text-gray-800">
                        <x-heroicon-o-x-mark class="h-5 w-5"/>
                    </button>
                    <span role="tooltip"
                          class="pointer-events-none invisible absolute bottom-[110%] left-1/2 z-20 w-max max-w-60 -translate-x-1/2 rounded bg-dark-600 px-2 py-1 text-center text-base text-white opacity-0 transition group-hover:visible group-hover:opacity-100 group-focus-within:visible group-focus-within:opacity-100">
                        Закрыть и больше не показывать
                    </span>
                </div>
            </div>

            <div class="flex items-start gap-1">
                <p class="flex-1 overflow-hidden text-lg leading-6 text-gray-700 transition-[max-height] duration-500 ease-in-out"
                   :class="[expanded ? 'max-h-40' : 'max-h-6', clamp ? 'line-clamp-1' : '']">
                    Живём литературной жизнью вместе: новые сборники, книги, вдохновение, писательские будни и немного книжных мемов 💚
                </p>
                <button type="button" @click="toggle()"
                        :aria-expanded="expanded.toString()"
                        :aria-label="expanded ? 'Свернуть подробности' : 'Подробнее'"
                        class="-mt-1 shrink-0 rounded-full p-1 text-gray-400 transition hover:bg-green-50 hover:text-green-600">
                    <x-heroicon-o-chevron-down class="h-5 w-5 transition"
                                               x-bind:class="expanded ? 'rotate-180' : ''"/>
                </button>
            </div>

            <div class="flex flex-wrap items-center gap-3">
                <x-ui.link :navigate="false" target="_blank" href="https://t.me/pervajakniga"
                           class="!min-w-0 !px-4 !py-1 !text-base">Открыть канал</x-ui.link>
            </div>
        </div>
    </x-ui.news-toast>

    <x-ui.news-toast inline storageKey="ai_helper_news_closed_v1" delay="4300" pulseDelay="1500" class="w-full">
        <div x-data="{
            expanded: false,
            clamp: true,
            clampTimer: null,
            toggle() {
                clearTimeout(this.clampTimer);
                if (this.expanded) {
                    this.expanded = false;
                    this.clampTimer = setTimeout(() => this.clamp = true, 500);
                    return;
                }
                this.clamp = false;
                this.expanded = true;
            }
        }" class="flex flex-col gap-3">
            <div class="flex items-start justify-between gap-3">
                <h4 class="whitespace-nowrap text-xl font-semibold">✨ Попробуйте ИИ-помощника</h4>
                <div class="group relative shrink-0">
                    <button type="button" @click="close()" aria-label="Закрыть и больше не показывать"
                            class="rounded-full p-1 text-gray-500 transition hover:bg-black/5 hover:text-gray-800">
                        <x-heroicon-o-x-mark class="h-5 w-5"/>
                    </button>
                    <span role="tooltip"
                          class="pointer-events-none invisible absolute bottom-[110%] left-1/2 z-20 w-max max-w-60 -translate-x-1/2 rounded bg-dark-600 px-2 py-1 text-center text-base text-white opacity-0 transition group-hover:visible group-hover:opacity-100 group-focus-within:visible group-focus-within:opacity-100">
                        Закрыть и больше не показывать
                    </span>
                </div>
            </div>

            <div class="flex items-start gap-1">
                <p class="flex-1 overflow-hidden text-lg leading-6 text-gray-700 transition-[max-height] duration-500 ease-in-out"
                   :class="[expanded ? 'max-h-40' : 'max-h-6', clamp ? 'line-clamp-1' : '']">
                    Выберите сценарий: например, придумайте идею для произведения, улучшите текст или подготовьте поздравление. ИИ создаст вариант, который можно отредактировать.
                </p>
                <button type="button" @click="toggle()"
                        :aria-expanded="expanded.toString()"
                        :aria-label="expanded ? 'Свернуть подробности' : 'Подробнее'"
                        class="-mt-1 shrink-0 rounded-full p-1 text-gray-400 transition hover:bg-green-50 hover:text-green-600">
                    <x-heroicon-o-chevron-down class="h-5 w-5 transition"
                                               x-bind:class="expanded ? 'rotate-180' : ''"/>
                </button>
            </div>

            <div class="flex flex-wrap items-center gap-3">
                <x-ui.link href="{{ route('account.ai-assistant') }}"
                           class="!min-w-0 !px-4 !py-1 !text-base">Открыть помощника</x-ui.link>
            </div>
        </div>
    </x-ui.news-toast>
</div>
