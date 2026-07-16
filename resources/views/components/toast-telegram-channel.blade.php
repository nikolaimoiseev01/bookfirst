<x-ui.news-toast>
    <h4 class="text-2xl"> 📚 Присоединяйтесь к нашему Telegram-каналу!</h4>
    <p class="mt-1 mb-4 text-lg leading-relaxed text-gray-700">
        Живём литературной жизнью вместе: новые сборники, книги, вдохновение, писательские будни и немного книжных мемов 💚
    </p>
    <div class="flex items-center gap-3 md:flex-col">
        <x-ui.link :navigate="false" target="_blank" href="https://t.me/pervajakniga"
                   class="!text-lg !py-0">Открыть канал</x-ui.link>
        <button
            type="button"
            @click="close()"
            class="text-base text-gray-600 hover:text-gray-900 transition"
        >
            Закрыть и больше не показывать
        </button>
    </div>
</x-ui.news-toast>
