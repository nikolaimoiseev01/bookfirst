<div class="mb-16">
    @section('title')
        Создание обсуждения @if($userToId != 2) с автором
            <x-ui.link-simple class="inline-flex text-4xl font-semibold"
                              href="{{route('social.user', $this->userTo['id'])}}">{{$this->userTo->getUserFullName()}}</x-ui.link-simple>
        @endif
    @endsection
    <form wire:submit="createChat()" class="flex flex-col gap-4 w-full max-w-xl">
        @if($userToId == 2)
            <x-ui.input.text wire:model="title" label="Заголовок"/>
        @endif
        <x-ui.input.text-area description="Опишите ваш вопрос" class="min-h-48" model="text"
                              :attachable="true" :sendable="false"/>
        <x-ui.button>Создать обсуждение</x-ui.button>
    </form>
</div>
