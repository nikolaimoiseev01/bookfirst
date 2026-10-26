<div @if($isRunning) wire:poll.2s="refreshStatus" @endif>
    @if($task)
        <section class="mb-4 rounded-lg border {{ $task->status === 'failed' ? 'border-red-300 bg-red-50 dark:border-red-900 dark:bg-red-950/30' : ($isRunning ? 'border-amber-300 bg-amber-50 dark:border-amber-800 dark:bg-amber-950/30' : 'border-green-200 bg-white/60 dark:border-green-900 dark:bg-dark_bg') }} p-3" aria-live="polite">
            @if($isRunning)
                <div class="flex items-center gap-3">
                    <x-ui.spinner class="h-5 w-5 shrink-0 !fill-green-500" />
                    <div class="min-w-0">
                        <p class="font-medium">{{ $task->generation_type === 'image' ? 'Создаём изображение' : 'Выполняем задачу' }}</p>
                        <p class="text-base text-dark-200">Результат появится здесь, если вы не закроете страницу, и будет доступен в истории.</p>
                    </div>
                </div>
            @elseif($task->status === 'failed')
                <p class="font-medium text-red-700 dark:text-red-300">Не удалось выполнить задачу</p>
                <p class="mt-1 whitespace-pre-line text-sm text-red-700 dark:text-red-300">{{ $task->error_message ?: 'Попробуйте запустить задачу еще раз.' }}</p>
            @elseif($task->generation_type === 'image' && $task->image_path)
                <h2 class="mb-3 text-xl font-semibold">Изображение готово</h2>
                <img src="{{ asset('storage/'.$task->image_path) }}" alt="Изображение, созданное ИИ" class="max-h-[600px] w-fit max-w-full rounded-lg object-contain">
                <a href="{{ asset('storage/'.$task->image_path) }}" download class="mt-3 inline-block text-green-600 underline hover:text-green-700">Скачать изображение</a>
            @else
                <h2 class="mb-2 text-xl font-semibold">Ответ ИИ</h2>
                <div class="whitespace-pre-line text-lg">{{ $task->answer }}</div>
                <p class="mt-2 text-sm text-dark-200">Проверьте текст и при необходимости отредактируйте его перед публикацией.</p>
            @endif
        </section>
    @endif
</div>
