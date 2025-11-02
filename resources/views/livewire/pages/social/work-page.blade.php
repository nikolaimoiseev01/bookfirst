<main class="flex-1 mb-16 content">
    @section('title')
        {{$work['title']}}
    @endsection
    <livewire:components.social.user-header class="mb-8" :user="$work->user"/>
    <div class="flex flex-col max-w-5xl gap-4">
        <h2 class="text-4xl">{{$work->title}}</h2>
        <p>{!! $work['text'] !!}</p>
        <div class="flex flex-col gap-2">
            <p><span class="font-medium">Рубрика:</span> {{ $work->workType['name'] }} / {{ $work->workTopic['name'] }}
            </p>
            <p><span class="font-medium">Опубликовано:</span> {{ formatDate($work['created_at'], 'j F Y H:i') }}</p>
            <div class="flex gap-2">
                <p class="font-medium">Нравится: </p>
                <x-ui.work-likes-button :workLikesCount="$workLikesCount" :userHasLike="$userHasLike"/>
            </div>

        </div>
    </div>
</main>
