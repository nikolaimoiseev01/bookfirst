<div class="mb-16">
    @section('title')
        ИИ-помощник
    @endsection

    <div class="flex flex-col gap-2 mb-6 max-w-4xl">
        <p class="font-medium">Бесплатных попыток осталось: <strong class="font-semibold text-green-500">{{ $freeAttemptsRemaining }} из {{ $attemptLimit }}</strong></p>
        @if($freeAttemptsRemaining > 0 && $attemptPackSize > 0 && $attemptPackPrice > 0)
            <p class="w-fit rounded-lg border border-gray-200 bg-gray-50 px-3 py-2 text-base dark:border-gray-700 dark:bg-dark_bg">
                Когда бесплатные попытки закончатся, можно будет купить {{ $attemptPackSize }} попыток за {{ number_format($attemptPackPrice, fmod($attemptPackPrice, 1) === 0.0 ? 0 : 2, ',', ' ') }} ₽.
            </p>
        @endif
        @if($purchasedAttempts > 0)
            <p class="font-medium">Платных попыток осталось: <strong class="font-semibold text-green-500">{{ $purchasedAttempts }}</strong></p>
        @endif
        @if($freeAttemptsRemaining === 0)
            @if($canBuyAttempts)
                <x-ui.button type="button" wire:click="buyAttempts" wire:target="buyAttempts">
                    Купить {{ $attemptPackSize }} попыток за {{ number_format($attemptPackPrice, fmod($attemptPackPrice, 1) === 0.0 ? 0 : 2, ',', ' ') }} ₽
                </x-ui.button>
                <p class="text-sm text-dark-200">Попытки начисляются автоматически после подтверждения оплаты YooKassa.</p>
            @else
                <p class="text-sm text-dark-200">Покупка пакета временно недоступна.</p>
            @endif
        @endif
    </div>

    <div x-data="{ activeTab: 'text' }" class="max-w-4xl">
        <div role="tablist" aria-label="Выбор режима ИИ-помощника" class="mb-4 flex gap-2 border-b border-gray-200 dark:border-gray-700">
            <button type="button" role="tab" :aria-selected="activeTab === 'text'"
                    @click="activeTab = 'text'"
                    :class="activeTab === 'text' ? 'border-green-500 text-green-600' : 'border-transparent text-dark-200 hover:text-green-600'"
                    class="-mb-px inline-flex items-center gap-2 border-b-2 px-4 py-3 text-lg font-medium transition">
                <x-heroicon-o-document-text class="h-5 w-5"/>
                Текст
            </button>
            <button type="button" role="tab" :aria-selected="activeTab === 'image'"
                    @click="activeTab = 'image'"
                    :class="activeTab === 'image' ? 'border-green-500 text-green-600' : 'border-transparent text-dark-200 hover:text-green-600'"
                    class="-mb-px inline-flex items-center gap-2 border-b-2 px-4 py-3 text-lg font-medium transition">
                <x-heroicon-o-photo class="h-5 w-5"/>
                Картинка
            </button>
            <button type="button" role="tab" :aria-selected="activeTab === 'history'"
                    @click="activeTab = 'history'"
                    :class="activeTab === 'history' ? 'border-green-500 text-green-600' : 'border-transparent text-dark-200 hover:text-green-600'"
                    class="-mb-px ml-auto inline-flex items-center gap-2 border-b-2 px-4 py-3 text-lg font-medium transition">
                <x-heroicon-o-clock class="h-5 w-5"/>
                История
            </button>
        </div>

    <section x-show="activeTab === 'text'" role="tabpanel" class="container mb-8 flex max-w-4xl flex-col gap-4 p-5">
        <div>
            <h2 class="text-3xl font-semibold">Создать текст</h2>
        </div>
        <details class="group rounded-lg border border-gray-200 bg-white/50 px-4 py-3 dark:border-gray-700 dark:bg-dark_bg">
            <summary class="flex cursor-pointer list-none items-center justify-between gap-3 text-base font-medium marker:hidden">
                Как это работает?
                <x-bi-chevron-down class="h-4 w-4 shrink-0 transition group-open:rotate-180"/>
            </summary>
            <div class="mt-2 space-y-1 text-sm">
                <p class="text-lg">Выберите задачу, добавьте исходный текст и пожелания, затем нажмите «Выполнить задачу».</p>
                <p class="text-lg">Сначала расходуются бесплатные попытки. После их использования можно купить дополнительный пакет.</p>
            </div>
        </details>
    @if($prompts->isEmpty())
        <p class="italic">Сейчас нет доступных задач. Попробуйте позже.</p>
    @else
    <form wire:submit="generate" class="flex w-full max-w-4xl flex-col gap-4">
        <div class="container flex items-center justify-between gap-4 p-4 sm:flex-col sm:items-start">
            <div class="flex min-w-0 flex-col gap-1">
                <span class="text-sm text-dark-200">Выбранная задача</span>
                <span class="text-xl font-medium">{{ $selectedPrompt?->title ?? 'Выберите задачу' }}</span>
                @if($selectedPrompt?->description)
                    <span class="text-dark-300">{{ $selectedPrompt->description }}</span>
                @endif
            </div>
            <x-ui.button type="button" @click="$dispatch('open-modal', 'aiPromptPicker')">
                Выбрать задачу
            </x-ui.button>
        </div>
        @if($selectedPrompt)
        <label for="ai-task-text" class="text-xl font-light">{{ $selectedPrompt->input_label }}</label>
        <textarea id="ai-task-text" wire:model="text" rows="8" maxlength="20000"
                  placeholder="{{ $selectedPrompt->input_placeholder }}"
                  class="w-full rounded-md border border-green-500 px-3 py-2 text-xl text-dark-400 placeholder:text-dark-200 focus:outline-none"></textarea>
        <p class="text-right text-base text-dark-200">До 20 000 символов</p>

        <button type="submit" wire:loading.attr="disabled" wire:target="generate"
                class="flex min-w-max items-center justify-center gap-2 rounded-lg border border-green-500 px-8 py-1 text-xl text-green-500 transition hover:bg-green-500 hover:text-white disabled:cursor-wait disabled:opacity-70">
            <span wire:loading.remove wire:target="generate">Выполнить задачу</span>
            <span wire:loading wire:target="generate" class="inline-flex items-center gap-2">
                <x-ui.spinner class="h-6 w-6 !fill-green-500"/>
            </span>
        </button>
        <p wire:loading wire:target="generate" class="text-base">Обычно генерация текста занимает около 10 секунд.</p>
        @endif
    </form>
    @endif

    @if($result)
        <section class="mt-2 flex flex-col gap-4 rounded-lg bg-white/60 p-4 dark:bg-dark_bg">
            <h3 class="text-2xl font-medium">Ответ ИИ</h3>
            <div class="whitespace-pre-line text-xl">{{ $result }}</div>
            <p class="text-dark-200">Проверьте текст и при необходимости отредактируйте его перед публикацией.</p>
        </section>
    @endif
    </section>

    <section x-show="activeTab === 'image'" role="tabpanel" class="container mb-8 flex max-w-4xl flex-col gap-4 p-5">
        <div>
            <h2 class="text-3xl font-semibold">Создать изображение</h2>
            <p class="mt-1 text-dark-200">Опишите, что должно быть на картинке. Чем подробнее описание, тем точнее результат.</p>
        </div>
        <details class="group rounded-lg border border-gray-200 bg-white/50 px-4 py-3 dark:border-gray-700 dark:bg-dark_bg">
            <summary class="flex cursor-pointer list-none items-center justify-between gap-3 text-base font-medium marker:hidden">
                Как это работает?
                <x-bi-chevron-down class="h-4 w-4 shrink-0 transition group-open:rotate-180"/>
            </summary>
            <div class="mt-2 space-y-1 text-sm">
                <p class="text-lg">Опишите желаемую картинку и нажмите «Создать изображение». Готовую картинку можно скачать или открыть позже в истории.</p>
                <p class="text-lg">Для текста и изображения используются общие попытки: сначала бесплатные, затем купленные.</p>
            </div>
        </details>
        <form wire:submit="generateImage" class="flex flex-col gap-4">
            <label for="ai-image-prompt" class="text-xl font-light">Описание изображения</label>
            <textarea id="ai-image-prompt" wire:model="imagePrompt" rows="5" maxlength="5000"
                      placeholder="Например: уютная книжная иллюстрация с рыжим котом у окна, мягкий вечерний свет"
                      class="w-full rounded-md border border-green-500 px-3 py-2 text-xl text-dark-400 placeholder:text-dark-200 focus:outline-none"></textarea>
            <p class="text-right text-base text-dark-200">До 5 000 символов</p>
            <button type="submit" wire:loading.attr="disabled" wire:target="generateImage"
                    class="flex min-w-max items-center justify-center gap-2 rounded-lg border border-green-500 px-8 py-1 text-xl text-green-500 transition hover:bg-green-500 hover:text-white disabled:cursor-wait disabled:opacity-70">
                <span wire:loading.remove wire:target="generateImage">Создать изображение</span>
                <span wire:loading wire:target="generateImage" class="inline-flex items-center gap-2">
                    <x-ui.spinner class="h-6 w-6 !fill-green-500"/>
                </span>
            </button>
            <p wire:loading wire:target="generateImage" class="text-base ">Обычно создание изображения занимает около 30 секунд.</p>
        </form>

        @if($generatedImageUrl)
            <div class="flex flex-col gap-3">
                <img src="{{ $generatedImageUrl }}" alt="Изображение, созданное ИИ" class="max-h-[700px] w-fit max-w-full rounded-lg object-contain">
                <a href="{{ $generatedImageUrl }}" download class="w-fit text-green-600 underline hover:text-green-700">Скачать изображение</a>
            </div>
        @endif
    </section>
    <section x-show="activeTab === 'history'" role="tabpanel" class="container mb-8 flex max-w-4xl flex-col gap-4 p-5">
        <div>
            <h2 class="text-3xl font-semibold">История запросов</h2>
            <p class="mt-1 text-dark-200">Здесь сохранены созданные вами тексты и изображения.</p>
        </div>

        <div class="flex flex-col gap-3">
            @forelse($history as $entry)
                <details class="rounded-lg border border-gray-200 bg-white/60 p-4 dark:border-gray-700 dark:bg-dark_bg">
                    <summary class="flex cursor-pointer list-none flex-wrap items-center gap-x-3 gap-y-1">
                        <span class="rounded-full bg-green-100 px-2.5 py-1 text-sm font-medium text-green-700 dark:bg-green-900/40 dark:text-green-300">
                            {{ $entry->generation_type === 'image' ? 'Картинка' : 'Текст' }}
                        </span>
                        <span class="font-semibold">{{ $entry->prompt_title }}</span>
                        <span class="ml-auto text-sm text-dark-200">{{ $entry->created_at?->format('d.m.Y H:i') }}</span>
                        <span class="w-full text-dark-200">{{ \Illuminate\Support\Str::limit($entry->question, 160) }}</span>
                    </summary>

                    <div class="mt-4 flex flex-col gap-3 border-t border-gray-200 pt-4 dark:border-gray-700">
                        <div>
                            <h3 class="text-sm font-medium">Ваш запрос</h3>
                            <p class="whitespace-pre-line text-dark-200">{{ $entry->question }}</p>
                        </div>
                        @if($entry->generation_type === 'image' && $entry->image_path)
                            <img src="{{ asset('storage/'.$entry->image_path) }}" alt="Изображение из истории запросов"
                                 class="max-h-[600px] w-fit max-w-full rounded-lg object-contain">
                            <a href="{{ asset('storage/'.$entry->image_path) }}" download
                               class="w-fit text-green-600 underline hover:text-green-700">Скачать изображение</a>
                        @else
                            <div>
                                <h3 class="text-sm font-medium">Ответ ИИ</h3>
                                <p class="whitespace-pre-line">{{ $entry->answer }}</p>
                            </div>
                        @endif
                    </div>
                </details>
            @empty
                <p class="text-dark-200">Пока здесь пусто. Ваши генерации появятся в истории.</p>
            @endforelse
        </div>

        {{ $history->links() }}
    </section>
    </div>

    <x-ui.modal name="aiPromptPicker" maxWidth="md">
        <div x-data="{ search: '' }" class="flex max-h-[75vh] flex-col gap-4 p-4">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <h2 class="text-2xl font-semibold text-dark-400">Выберите задачу</h2>
                    <p class="mt-1 text-dark-200">Можно найти задачу по названию или описанию.</p>
                </div>
                <button type="button" @click="$dispatch('close-modal', 'aiPromptPicker')"
                        class="text-dark-300 transition hover:text-green-500" aria-label="Закрыть">
                    <x-heroicon-o-x-mark class="h-6 w-6"/>
                </button>
            </div>

            <input type="search" x-model="search" placeholder="Поиск задач…"
                   class="w-full rounded-md border border-green-500 px-3 py-2 text-xl text-dark-400 placeholder:text-dark-200 focus:outline-none">

            <div class="flex flex-col gap-2 overflow-y-auto pr-1">
                @foreach($prompts as $prompt)
                    <button type="button" wire:click="selectPrompt({{ $prompt->id }})"
                            x-show="!search.trim() || $el.innerText.toLocaleLowerCase().includes(search.trim().toLocaleLowerCase())"
                            @class([
                                'container flex w-full flex-col gap-1 p-4 text-left transition hover:border-green-500',
                                'border-green-500' => $selectedPromptId === $prompt->id,
                            ])>
                        <span class="text-xl font-medium">{{ $prompt->title }}</span>
                        @if($prompt->description)
                            <span class="text-dark-300">{{ $prompt->description }}</span>
                        @endif
                    </button>
                @endforeach
                <p x-show="search.trim() && ![...$el.parentElement.querySelectorAll('button')].some(button => button.innerText.toLocaleLowerCase().includes(search.trim().toLocaleLowerCase()))"
                   class="hidden py-4 text-center text-dark-200">
                    По вашему запросу ничего не найдено.
                </p>
            </div>
        </div>
    </x-ui.modal>

</div>
