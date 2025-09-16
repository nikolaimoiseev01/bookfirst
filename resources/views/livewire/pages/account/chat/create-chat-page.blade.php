<div class="mb-16">
    @section('title')
        Создание обсуждения
    @endsection
    <form wire:submit="createChat()" class="flex flex-col gap-4 w-full max-w-xl">
        <x-ui.input.text wire:model="title" label="Заголовок"/>
        <x-ui.input-text-area description="Опишите ваш вопрос" class="min-h-48" model="text"/>
        <x-ui.button>Создать обсуждение</x-ui.button>
    </form>
</div>

