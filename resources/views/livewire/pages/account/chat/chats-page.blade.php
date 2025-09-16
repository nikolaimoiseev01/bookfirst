<div class="mb-16">
    @section('title')
        Мои обсуждения
    @endsection
    <div class="flex gap-2 mb-6 ">
        <x-ui.link-simple href="{{route('account.chat_create')}}" class="text-xl">
            Создать общий вопрос
        </x-ui.link-simple>
    </div>
    <div class="flex max-h-[600px] max-w-5xl" x-data="{tab: 'support'}">
        <div class="flex flex-col max-w-80 border-r border-dark-100 pr-2">
            <x-ui.input.toggle :options="['personal' => 'Личные', 'support' => 'Поддержка']" model="tab"/>
            <div x-show="tab == 'support'" class="flex flex-col overflow-y-auto pt-4 pr-2">
                @forelse($allChats->where('flg_admin_chat') as $chat)
                    <x-chat.chat-summary-card :chat="$chat" :chosen="$cur_chat['id'] == $chat['id']"/>
                @empty
                    <p>Еще нет чатов</p>
                @endforelse
            </div>
            <div x-show="tab == 'personal'">
                @forelse($allChats->where('flg_admin_chat', false) as $chat)
                    <x-chat.chat-summary-card :chat="$chat" :chosen="$cur_chat['id'] == $chat['id']"/>
                @empty
                    <p>Еще нет чатов</p>
                @endforelse
            </div>
        </div>
        <div class="flex flex-col px-4 flex-1 relative">
            <div wire:loading
                 class="absolute w-full h-full top-0 left-0 bg-dark-100/[var(--bg-opacity)] [--bg-opacity:70%] rounded flex items-center justify-center z-50">
                <x-ui.spinner class="w-16 h-16 absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 "/>
            </div>
            <div class="flex flex-col">
                <span class="font-normal text-2xl">{{$cur_chat['title']}}</span>
                @if($cur_chat->ownBook ?? null)
                    <x-ui.link-simple href="{{route('account.own_book', $cur_chat->ownBook['id'])}}" class="text-lg"> На
                        страницу издания
                    </x-ui.link-simple>
                @elseif($cur_chat->participation ?? null)
                    <x-ui.link-simple href="{{route('account.participation', $cur_chat->participation['id'])}}"
                                      class="text-lg">
                        На страницу издания
                    </x-ui.link-simple>
                @elseif($cur_chat->extPromotion ?? null)
                    <x-ui.link-simple href="{{route('account.ext_promotion', $cur_chat->extPromotion['id'])}}"
                                      class="text-lg">
                        На страницу издания
                    </x-ui.link-simple>
                @endif
            </div>
            <livewire:components.account.chat key="{{ rand() }}" :chat="$cur_chat"/>
        </div>
    </div>
</div>

