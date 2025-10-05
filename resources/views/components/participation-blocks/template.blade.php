<div class="px-16 pb-16 border-x-2 border-{{$color}} first:rounded-t-2xl first:pt-16 first:border-t-2 relative" x-data="{show: false}">
    <img src="{{$icon}}" alt="" class="absolute top-1/2 w-16 h-16 -translate-y-1/2 -left-8 bg-white z-30">
    <div class="absolute top-1/2 -translate-y-1/2 w-1/2 h-[2px] bg-{{$color}} left-0 z-20"></div>
    <div class="container {{$shadow}} flex flex-col relative z-40">
        <div class="border-b border-{{$color}} w-full p-4">
            <h3 class="text-4xl text-{{$color}}">{{$title}}</h3>
        </div>
        <div>
            {{$slot}}
        </div>
    </div>
</div>
