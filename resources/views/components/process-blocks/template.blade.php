<div id="{{$title}}"
    class="px-16 pb-16 border-x-2 border-{{$color}} first:rounded-t-2xl first:pt-16 first:border-t-2 relative last:border-b-2 last:rounded-b-2xl"
    x-data="{show: true}">
    <img src="{{$icon}}" alt="" class="absolute top-1/2 w-16 h-16 -translate-y-1/2 -left-8 bg-white z-30">
    <div class="absolute top-1/2 -translate-y-1/2 w-1/2 h-[2px] bg-{{$color}} left-0 z-20"></div>
    <div class="container {{$shadow}} flex flex-col relative z-40">
        <div  @click="show = !show" :class="show ? 'border-b border-{{$color}}' : ''" class=" w-full p-4 flex justify-between cursor-pointer">
            <h3 class="text-4xl text-{{$color}}">{{$title}}</h3>
            <x-bi-chevron-down x-bind:class="show ? 'rotate-180' : ''"
                                class="w-8 h-auto cursor-pointer transition fill-dark-300"/>
        </div>
        <div x-show="show" x-cloak x-collapse.duration.400ms>
            {{$slot}}
        </div>
    </div>
</div>
