<div class="flex gap-4">
    <div class="flex flex-col gap-4 w-full max-w-xl">
        <x-ui.input.text required wire:model="title" label="Название"/>
        <x-ui.input.text-area wire:key="textarea-{{ $deleteImageFlg ? 'with-flag' : 'no-flag' }}" required description="Текст произведения" class="min-h-48" model="text" :attachable="$deleteImageFlg"
                              :sendable="false" :multiple="false"/>
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
        <x-ui.button wire:click="createAndOut()">
            @if($cameFromAppUrl)
                Сохранить и вернуться к заявке
            @else
                Сохранить
            @endif
        </x-ui.button>
        @if($formType == 'create')
            <x-ui.link-simple wire:click="createAndAnouther()">
                Сохранить и загрузить еще одно
            </x-ui.link-simple>
        @endif
    </div>
    @if($work && ($work->getFirstMediaUrl('cover') ?? null) && !$deleteImageFlg)
        <div class="flex flex-col">
            <h3>Обложка произведения</h3>
            <div class="relative max-h-80 h-80">
                <x-ui.tooltip-wrap text="Удалить обложку (можно загрузить новую в поле ввода текста)" class="cursor-pointer" wire:click="removeCover">
                    <x-bi-x class="absolute fill-white bg-red-300 top-1 left-1 w-6 h-auto rounded-full"/>
                </x-ui.tooltip-wrap>
                <img src="{{$work->getFirstMediaUrl('cover')}}" class="h-full object-cover" alt="">
            </div>
        </div>
    @endif
</div>
