<div class="flex flex-col gap-4 container w-fit p-4">
    <div class="flex gap-2">
        <img src="{{getUserAvatar($author)}}" class="w-10 rounded-full" alt="">
        <x-ui.link-simple class="text-3xl"
            href="{{route('social.user', $author['id'])}}">{{\Illuminate\Support\Str::limit($author->getUserFullName(), 20)}}</x-ui.link-simple>
    </div>
    <div class="flex justify-between">
        <x-ui.tooltip-wrap class="!flex items-center flex-col text-center text-blue-500"
                           text="Подписчиков">
            <x-heroicon-o-user class="w-6 h-6"/>
            <span class='text-xl'>{{$author->subscribers_count}}</span>
        </x-ui.tooltip-wrap>
        <x-ui.tooltip-wrap class="!flex items-center flex-col text-center text-green-500"
                           text="Собственных книг">
            <x-heroicon-o-book-open class="w-6 h-6"/>
            <span class='text-xl'>{{$author->own_books_count}}</span>
        </x-ui.tooltip-wrap>
        <x-ui.tooltip-wrap class="!flex items-center flex-col text-center text-brown-500"
                           text="Участий в сборниках">
            <x-bi-collection class="w-6 h-6"/>
            <span class='text-xl'>{{$author->participations_count}}</span>
        </x-ui.tooltip-wrap>
        <x-ui.tooltip-wrap class="!flex items-center flex-col text-center text-red-300"
                           text="Произведений">
            <x-heroicon-o-pencil class="w-6 h-6"/>
            <span class='text-xl'>{{$author->works_count}}</span>
        </x-ui.tooltip-wrap>
    </div>
</div>
