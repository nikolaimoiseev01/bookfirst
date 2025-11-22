@props([
    'work'
])
<div class="flex rounded flex-col container max-w-56 overflow-hidden">
    <div class="relative group mb-2">
        <div class="absolute top-0 left-0 w-full h-full transition
        backdrop-blur-sm bg-[#b3b3b385] z-20 opacity-0 group-hover:opacity-100
        flex justify-center items-center
        ">
            <a href="{{route('social.work', $work['id'])}}" class="px-5 font-medium rounded-xl py-1 text-white border border-white transition hover:bg-white hover:text-dark-500 text-xl">Читать</a>
        </div>
        <img src="{{getWorkCover($work)}}" alt="">
    </div>
    <div class="flex flex-col px-3 pb-1">
        <x-ui.link-simple href="{{route('social.user', $work['user_id'])}}" class="text-lg">{{\Illuminate\Support\Str::limit($work->user->getUserFullName(), 20)}}</x-ui.link-simple>
        <p class="text-xl font-medium">{{\Illuminate\Support\Str::limit($work['title'], 18)}}</p>
    </div>
</div>
