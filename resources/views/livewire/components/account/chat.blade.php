<div class="w-full h-full chat-wrap">
    <div class="bg-white dark:bg-dark_bg dark:border dark:border-gray-300 h-full flex flex-col">
        <!-- список сообщений -->
        <div id="chatMessagesWrap"
             class="flex flex-col gap-4 px-4 py-2 flex-[1_1_0] overflow-y-auto min-h-80 transition">
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
        <x-ui.input.text-area model="text" attachable="true"></x-ui.input.text-area>
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
