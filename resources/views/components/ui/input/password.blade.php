@props([
    'name',          // имя поля (обязательное)
    'label' => null, // подпись над полем
])

<div class="flex flex-col gap-1 w-full" x-data="{show: false}">
    @if($label)
        <label for="{{ $name }}" class="text-xl font-light">
            {{ $label }}
        </label>
    @endif

    <div class="relative">
        <input
            :type="show ? 'text' : 'password'"
            id="{{ $name }}"
            name="{{ $name }}"
            {{ $attributes->merge([
                'class' =>
                    'border rounded-md px-3 py-2 w-full focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 ' .
                    ($errors->has($name) ? 'border-red-500' : 'border-gray-300')
            ]) }}
        />
        <x-ui.tooltip-wrap
            class="!absolute top-1/2 -translate-y-1/2 right-4"
            @click="show = !show" x-show="!show" text="Показать пароль">
            <x-bi-eye
                class="w-6 h-auto text-green-500 cursor-pointer transition hover:scale-110"/>
        </x-ui.tooltip-wrap>
        <x-ui.tooltip-wrap
            class="!absolute top-1/2 -translate-y-1/2 right-4"
            @click="show = !show" x-show="show" text="Скрыть пароль">
            <x-bi-eye-slash
                class="w-6 h-auto text-green-500 cursor-pointer transition hover:scale-110"/>
        </x-ui.tooltip-wrap>
    </div>


{{--    @error($name)--}}
{{--    <p class="text-sm text-red-500">{{ $message }}</p>--}}
{{--    @enderror--}}
</div>
