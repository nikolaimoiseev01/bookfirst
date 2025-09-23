<div class="mb-16">
    @section('title')
        Добавление произведения
    @endsection
    <form wire:submit="createWork()" class="flex flex-col gap-4 w-full max-w-xl">
        <x-ui.input.text required wire:model="title" label="Название"/>
        <x-ui.input.text-area required description="Текст произведения" class="min-h-48" model="text" :attachable="true"
                              :sendable="true" :multiple="false"/>
        <div class="flex gap-4">
            <x-ui.dropdown
                wire:model.live="workType"
                default="Выберите тип"
                class="flex-1"
                :options="$workTypeOptions"
            />
            <x-ui.dropdown
                wire:model.live="workTopic"
                default="Выберите тему"
                class="flex-1"
                :options="$workTopicOptions"
            />
        </div>
        <x-ui.button >
            @if($cameFromAppUrl)
                Сохранить и вернуться к заявке
            @else
                Сохранить
            @endif
        </x-ui.button>
    </form>
</div>

