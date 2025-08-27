@props(['cover'])

<div {{$attributes ->merge(['class' => 'w-full aspect-[225/350] relative text-center group cursor-pointer'])}}>

   <!-- Book Cover -->
    <div
        class="absolute z-1 w-full h-full rounded-[3px] transition-all duration-500 origin-left bg-cover bg-[#111] group-hover:[transform:perspective(2000px)_rotateY(-30deg)]"
        style="background-image: url('{{$cover}}'); background-size: 100% 100%;"
    >
        <!-- Effect -->
        <div
            class="h-full ml-[10px] border-l border-[#0001] transition-all duration-500 bg-linear-to-r from-white/20 to-transparent group-hover:w-[40px] w-[20px]"
        ></div>
        <div
            class="absolute top-0 h-full mr-[10px] border-r border-[#0001] transition-all duration-500 bg-linear-to-r from-white/20 to-transparent group-hover:w-[10px] w-[10px]"
        ></div>

        <!-- Light -->
        <div
            class="absolute top-0 right-0 h-full w-[90%] rounded-[3px] opacity-10 transition-all duration-500 bg-linear-to-r from-transparent to-white/20 group-hover:opacity-100 group-hover:w-[70%]"
        ></div>
    </div>

    <!-- Book Inside -->
    <div
        class="relative top-[2%] w-[calc(100%-2px)] h-[96%] rounded-[3px] border border-gray-400 bg-white shadow-[9px_8px_36px_9px_#00000024,inset_-2px_0_0_gray,inset_-3px_0_0_#dbdbdb,inset_-4px_0_0_white,inset_-5px_0_0_#dbdbdb,inset_-6px_0_0_white,inset_-7px_0_0_#dbdbdb,inset_-8px_0_0_white,inset_-9px_0_0_#dbdbdb]"
    ></div>
</div>

{{--<style>--}}
{{--    .cover {--}}
{{--        background: linear-gradient(to right, rgb(60, 13, 20) 3px, rgba(255, 255, 255, 0.5) 5px, rgba(255, 255, 255, 0.25) 7px, rgba(255, 255, 255, 0.25) 10px, transparent 12px, transparent 16px, rgba(255, 255, 255, 0.25) 17px, transparent 22px), url({{$cover}});--}}
{{--        box-shadow: 0 0 5px -1px black, inset -1px 1px 2px rgba(255, 255, 255, 0.5);--}}
{{--        margin: auto;--}}
{{--        border-radius: 5px;--}}
{{--        width: 389px;--}}
{{--        height: 500px;--}}
{{--    }--}}
{{--</style>--}}

{{--<div title=" Don't make me think " class="cover"></div>--}}
