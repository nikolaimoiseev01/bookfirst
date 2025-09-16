<div {{$attributes ->merge(['class' => 'aspect-[230/350] relative text-center group'])}}>
    <img src="{{$cover}}" class="absolute w-full h-full top-0 left-0 z-20" alt="">
    <div class="z-10 w-full h-full bg-green-500 animate-pulse-light  [animation-duration:3s] flex items-center justify-center">
        <x-ui.spinner class="w-6 h-6"/>
    </div>
</div>
