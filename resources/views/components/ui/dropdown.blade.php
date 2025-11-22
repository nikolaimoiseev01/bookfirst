@props([
    'options' => [],           // ['id'=>'Название', ...] или [['value'=>1,'label'=>'Текст']]
])

@php
    // Нормализуем к виду [['value'=>x,'label'=>y]]
    $normalized = collect($options)->map(function($v,$k){
        return is_array($v)
            ? ['value'=>$v['value'],'label'=>$v['label']]
            : ['value'=>$k,'label'=>$v];
    })->values();
@endphp

<div
    x-data="{
        open: false,
        selected: @entangle($attributes->wire('model')),
        options: @js($normalized),
    }"
    {{ $attributes->merge(['class' => 'relative w-fit']) }}
>
    <button
        type="button"
        @click="open = !open"
        @click.outside="open = false"
        :class="$store.global.social ? ' border-blue-500 ' : ' border-green-500 '"
        class="w-full border rounded-md bg-white px-4 py-1 text-left flex font-light text-xl justify-between items-center"
    >
        <span class="text-lg" x-text="options.find(o => o.value == selected)?.label ?? '{{ $placeholder }}'"></span>
        <svg class="w-4 h-4 ml-2 transform transition-transform"
             :class="open ? 'rotate-180' : ''"
             fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M19 9l-7 7-7-7"/>
        </svg>
    </button>

    <!-- Options -->
    <ul
        x-show="open"
        x-transition
        class="absolute z-10 mt-1 w-fit border rounded-md bg-white shadow-md max-h-60 overflow-y-auto"
    >
        <template x-for="opt in options" :key="String(opt.value)">
            <li
                @click="selected = opt.value; open = false"
                class="px-3 py-2 text-lg cursor-pointer hover:bg-gray-100"
                :class="selected == opt.value ? 'bg-gray-50 font-medium' : ''"
                x-text="opt.label"
            ></li>
        </template>
    </ul>
</div>
