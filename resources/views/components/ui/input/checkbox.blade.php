@props([
  'id' => Str::uuid(),     // можно передать свой
  'label' => null,
  'disabled' => false,
])

<label for="{{ $id }}" class="relative inline-flex items-center gap-2 cursor-pointer select-none">
    <input
        id="{{ $id }}"
        type="checkbox"
        {{ $disabled ? 'disabled' : '' }}
        {{ $attributes->whereStartsWith('wire:') }}
        {{ $attributes->whereStartsWith('x-model') }}
        class="
      peer
      relative w-6 h-6
      appearance-none outline-none
      border border-green-500 rounded
      cursor-pointer
      transition-colors duration-[175ms] ease-[cubic-bezier(0.1,0.1,0.25,1)]
      checked:bg-green-500 checked:border-green-500

      before:content-[''] before:absolute before:block
      before:top-[1px] before:left-[8px] before:w-[8px] before:h-[16px]
      before:border-solid before:border-white before:border-r-2 before:border-b-2
      before:rotate-45 before:opacity-0
      checked:before:opacity-100

      disabled:opacity-50 disabled:cursor-not-allowed
      focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:ring-green-500/40
    "
    />

    <span class="text-sm text-gray-800 relative
               before:absolute before:left-0 before:top-1/2 before:-translate-y-1/2
               before:h-[1.2em] before:w-full before:rounded
               before:[clip-path:polygon(0_0,0_0,0_100%,0_100%)]
               before:bg-green-500/10
               before:transition-[clip-path] before:duration-300
               peer-checked:before:[clip-path:polygon(0_0,100%_0,100%_100%,0_100%)]
  ">
    {{ $label ?? $slot }}
  </span>
</label>
