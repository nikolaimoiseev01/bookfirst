<div class="mb-16">
    @section('title')
        Мои обсуждения
    @endsection
    <div class="flex gap-2 mb-6 ">
        <x-ui.link-simple href="{{route('account.chat_create')}}" class="text-xl">
            Создать общий вопрос
        </x-ui.link-simple>
    </div>
    @if(count($allChats) > 0)
        <div class="flex max-h-[600px] max-w-5xl md:flex-col md:max-h-max md:gap-4" x-data="{tab: @entangle('tab')}">
            <div class="flex flex-col max-w-80 border-r border-dark-100 pr-2 md:max-h-[200px] md:max-w-full md:w-full">
                <x-ui.input.toggle :options="['personal' => 'Личные', 'support' => 'Поддержка']" model="tab"/>
                <div x-show="tab == 'support'" class="flex flex-col overflow-y-auto pt-4 pr-2">
                    @forelse($allChats->where('flg_admin_chat') as $chat)
                        <x-chat.chat-summary-card :chat="$chat"
                                                  :chosen="$curChat['id'] == $chat['id']"/>
                    @empty
                        <p>Еще нет чатов</p>
                    @endforelse
                </div>
                <div x-show="tab == 'personal'" class="flex flex-col overflow-y-auto pt-4 pr-2">
                    @forelse($allChats->where('flg_admin_chat', 0) as $chat)
                        <x-chat.chat-summary-card :chat="$chat"
                                                  :chosen="$curChat['id'] == $chat['id']"/>
                    @empty
                        <p>Еще нет чатов</p>
                    @endforelse
                </div>
            </div>
            <div class="flex flex-col px-4 flex-1 relative">
                <div wire:loading
                     class="absolute w-full h-full top-0 left-0 bg-dark-100/[var(--bg-opacity)] [--bg-opacity:70%] rounded flex items-center justify-center z-50">
                    <x-ui.spinner
                        class="w-16 h-16 absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 "/>
                </div>
                <div class="flex flex-col">
                    <span class="font-normal text-2xl">{{$curChat['title']}}</span>
                    @if($curChat->model ?? null)
                        <x-ui.link-simple href="{{$curChat->model->accountIndexPage()}}"
                                          class="text-lg">
                            Подробнее
                        </x-ui.link-simple>
                    @endif
                </div>
                <livewire:components.account.chat key="{{ rand() }}" :chat="$curChat"/>
            </div>
        </div>
    @else
        <p class="italic">Еще чатов с вашим участием, но все еще впереди 🙂</p>
    @endif
</div>

