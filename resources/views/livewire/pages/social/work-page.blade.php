<main class="flex-1 mb-16 content">

    @section('title')
        {{$work['title'] ?? 'Такого произведения нет'}}
    @endsection
    @if($work)
        <livewire:components.social.user-header class="mb-8" :user="$user"/>
        <section class="flex flex-col max-w-5xl gap-4 mb-10">
            <h2 class="text-4xl">{{$work->title}}</h2>
            <p>{!! nl2br(e($work['text'])) !!}</p>
            <div class="flex flex-col gap-2">
                <p><span class="font-medium">Рубрика:</span> {{ $work->workType['name'] }}
                    / {{ $work->workTopic['name'] }}
                </p>
                <p><span
                        class="font-medium">Опубликовано:</span> {{ formatDate($work['created_at'], 'j F Y H:i') }}
                </p>
                <div class="flex gap-2">
                    <p class="font-medium">Нравится: </p>
                    <x-ui.work-likes-button :workLikesCount="$workLikesCount"
                                            :userHasLike="$userHasLike"/>
                </div>
            </div>
        </section>

        <section class="flex flex-col gap-4 max-w-4xl"
                 x-data="{showAddComment: @entangle('showAddComment')}">
            <div class="flex gap-6">
                <h3 class="text-4xl">Комментарии</h3>
                <x-ui.link-simple data-check-logged x-show="!showAddComment"
                                  class="flex items-center gap-2 mt-auto"
                                  @click="showAddComment = !showAddComment">
                    <x-bi-plus-circle/>
                    Добавить
                </x-ui.link-simple>
                <x-ui.link-simple x-show="showAddComment" class="flex items-center gap-2 mt-auto"
                                  @click="showAddComment = !showAddComment">
                    Скрыть
                </x-ui.link-simple>
            </div>
            @auth()
                <div x-show="showAddComment">
                    <x-ui.input.text-area color="blue-500" :attachable="false"/>
                </div>
            @endauth
            <div class="flex flex-col gap-4">
                @forelse($workComments as $comment)
                    <div class="flex flex-col gap-6">
                        <div class="flex gap-2">
                            <img src="{{getUserAvatar($comment->user)}}" class="w-8 rounded-full"
                                 alt="">
                            <x-ui.link-simple
                                href="{{route('social.user', $comment->user['id'])}}">{{$comment->user->getUserFullName()}}</x-ui.link-simple>
                            <p class="text-gray-400 text-lg mt-auto">
                                ({{formatDate($comment['created_at'], 'j F Y H:i')}})</p>
                        </div>
                        <p>{{$comment['text']}}</p>
                    </div>
                @empty
                    <p>Еще нет ни одного, будьте первым!</p>
                @endforelse
            </div>
        </section>
    @else
        <p>Такого произведения не существует</p>
    @endif
</main>
