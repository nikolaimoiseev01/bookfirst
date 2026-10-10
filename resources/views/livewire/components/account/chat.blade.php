<div
    x-data="{ fullPage: false }"
    :style="fullPage
        ? 'position: fixed; inset: 0; width: 100vw; height: 100vh; z-index: 999; padding: 50px 20px; background: #000000ad;'
        : ''
    "
    style="height: 100%;"
>
    @hasanyrole(['admin', 'secondary_admin'])
    <x-chat.status-title :chat="$chat"/>
    @endhasanyrole

    <div :style="fullPage ? 'height: 100%' : ''" class="w-full h-[100%] chat-wrap">
        <div
            class="bg-white dark:bg-dark_bg dark:border dark:border-gray-300 h-full flex flex-col dark:!border-none">
            <!-- список сообщений -->
            <div id="chatMessagesWrap"
                 class="flex relative flex-col gap-4 px-4 py-2 flex-[1_1_0] overflow-y-auto min-h-80 transition">
                @if(count($chat['messages']) > 0 )
                    @foreach($chat['messages'] as $message)
                        <x-chat.message :message="$message"/>
                    @endforeach
                @else
                    <span class="text-gray-100! text-4xl font-bold">
                                    Это чат с Вашим личным менеджером по конкретно этому изданию. В нем пока нет сообщений.
                    Здесь Вы можете задать любые вопросы, а также прикреплять файлы при необходимости.
            </span>
                @endif
            </div>

            <!-- форма -->
            <div wire:loading.flex wire:target="generateAiReply" role="status" aria-live="polite"
                 class="mx-4 mb-2 items-center gap-3 rounded-lg bg-primary-50 px-4 py-3 text-sm text-primary-700 dark:bg-primary-950 dark:text-primary-200">
                <x-ui.spinner class="h-5 w-5 shrink-0" />
                <span>ИИ анализирует переписку и готовит ответ…</span>
            </div>
            <x-ui.input.text-area :messageTemplatesShow="true" model="text"
                                  attachable="true"></x-ui.input.text-area>
            @if($aiDraft !== '')
                <div class="mx-4 mt-2 flex justify-end">
                    <label class="inline-flex cursor-pointer items-center gap-2 rounded-lg border border-gray-200 bg-white px-2.5 py-1.5 text-xs font-medium text-gray-600 shadow-sm transition hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 dark:hover:bg-gray-800">
                        <x-filament::input.checkbox wire:model="saveAiExample" class="!size-3.5" />
                        <span>Сохранить ответ как пример</span>
                    </label>
                </div>
            @endif

            @push('scripts')
                <script>
                    function scroll() {
                        const el = document.getElementById('chatMessagesWrap');
                        if (el) el.scrollTop = el.scrollHeight;
                    }

                    window.addEventListener('scrollChatToEnd', () => {
                        setTimeout(function () {
                            scroll()
                        }, 100)
                    });
                    scroll()
                </script>
            @endpush
        </div>
    </div>
</div>
