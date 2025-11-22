<div class="flex flex-col gap-8">
    <div class="flex gap-4 items-center">
        <x-ui.dropdown
            wire:model.live="workType"
            default="Выберите тип"
            class=""
            :options="$workTypeOptions"
        />
        <x-ui.dropdown
            wire:model.live="workTopic"
            default="Выберите тему"
            class=""
            :options="$workTopicOptions"
        />
        <x-ui.dropdown
            wire:model.live="sortOption"
            default="Выберите тему"
            class=""
            :options="$sortOptions"
        />
        <x-ui.spinner class="w-6 h-6" wire:loading/>
        <div class="flex gap-2 ml-auto">
            <x-ui.tooltip-wrap text="Блоки">
                <svg wire:click="changeLayout('blocks')"
                     class="@if($layout == 'blocks') fill-blue-300 @else fill-dark-300 @endif cursor-pointer w-7"
                     fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                    <path
                        d="M448 160l-128 0 0-32 128 0 0 32zM48 64C21.5 64 0 85.5 0 112l0 64c0 26.5 21.5 48 48 48l416 0c26.5 0 48-21.5 48-48l0-64c0-26.5-21.5-48-48-48L48 64zM448 352l0 32-256 0 0-32 256 0zM48 288c-26.5 0-48 21.5-48 48l0 64c0 26.5 21.5 48 48 48l416 0c26.5 0 48-21.5 48-48l0-64c0-26.5-21.5-48-48-48L48 288z"></path>
                </svg>
            </x-ui.tooltip-wrap>

            <x-ui.tooltip-wrap text="Список">
                <svg wire:click="changeLayout('lines')"
                     class="@if($layout == 'lines') fill-blue-300 @else fill-dark-300 @endif cursor-pointer w-6"
                     fill="currentColor"
                     xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512">
                    <path
                        d="M0 96C0 78.3 14.3 64 32 64l384 0c17.7 0 32 14.3 32 32s-14.3 32-32 32L32 128C14.3 128 0 113.7 0 96zM0 256c0-17.7 14.3-32 32-32l384 0c17.7 0 32 14.3 32 32s-14.3 32-32 32L32 288c-17.7 0-32-14.3-32-32zM448 416c0 17.7-14.3 32-32 32L32 448c-17.7 0-32-14.3-32-32s14.3-32 32-32l384 0c17.7 0 32 14.3 32 32z"></path>
                </svg>
            </x-ui.tooltip-wrap>

        </div>
    </div>
    @if ($layout == 'blocks')
        @foreach($works as $work)
            <livewire:components.social.work-card :work="$work" wire:key="work-{{$work['id']}}"/>
        @endforeach
    @else
        <div class="flex flex-col gap-4">
            @foreach($works as $work)
                <div
                    class="flex gap-2 py-2 px-1 cursor-pointer border-b border-dark-300 items-center hover:bg-dark-50 justify-between">
                    <div class="flex gap-2">
                        <x-ui.link-simple href="{{route('social.user', $work['user_id'])}}"
                                          class="text-blue-500 text-2xl font-medium">
                            {{ getUserName($work->user) }}:
                        </x-ui.link-simple>
                        <x-ui.link-simple href="{{route('social.work', $work['id'])}}"
                                          class="text-2xl">{{\Illuminate\Support\Str::limit($work['title'], 70) }}</x-ui.link-simple>
                    </div>
                    <div class="flex gap-2">
                        <span
                            class="text-dark-350 ml-auto">{{ $work['work_type_id'] == 999 ? '' : $work->workType['name'] . '/' }} {{ $work['work_topic_id'] == 999 ? '' : $work->workTopic['name'] }}</span>
                        <span
                            class="text-dark-350 ml-auto font-light">{{ formatDate($work['created_at'], 'j F Y H:i') }}</span>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
    <div class="flex">
        @if ($works->count() < $totalWorks)
            <x-ui.link-simple wire:click="loadMore" wire:loading.remove>
                Загрузить ещё
            </x-ui.link-simple>
            <x-ui.spinner class="w-8" wire:loading/>
        @else
            <span class="text-dark-350 text-xl font-light italic">Все работы ({{$works->count()}}) загружены</span>
        @endif
    </div>

</div>
