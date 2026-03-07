<div class="mb-16 max-w-5xl">
    @section('title')
        Процесс печати: {{$printOrder->model['title']}}
    @endsection
    <div class="mb-4">
        <livewire:components.account.purchase-print.survey-purchase-print
            :print-order="$printOrder"/>
    </div>
    <div
        class="mb-8 px-4 py-2 flex flex-col justify-between items-center gap-2 border rounded-2xl border-green-500">
        <p>Мой статус печати: <span class="font-normal">{{$printOrder['status']}}</span></p>
        <div class="flex gap-4 text-lg flex-wrap">
            <div class="flex gap-2 items-center"><span
                    class="w-4 h-4 rounded-full bg-dark-200"></span>Пункт недоступен
            </div>
            <div class="flex gap-2 items-center"><span
                    class="w-4 h-4 rounded-full bg-brown-300"></span>Необходимо
                действие
            </div>
            <div class="flex gap-2 items-center"><span
                    class="w-4 h-4 rounded-full bg-green-500"></span>Успешно
                выполнено
            </div>
        </div>
    </div>
    <div class="flex flex-col mb-8" x-data="{showChat: false}">
        <x-ui.button @click="showChat=!showChat"
                     x-text="!showChat ? 'Чат с личным менеджером' : 'Свернуть чат'"/>
        <div x-show="showChat"
             x-cloak
             x-collapse.duration.400ms>
            <div class="mt-4 container">
                <livewire:components.account.chat :chat="$printOrder->chat"/>
            </div>
        </div>
    </div>
        <div class="flex flex-col pl-4 md:pl-0 md:gap-8">
            @if($printOrder['status'] != \App\Enums\PrintOrderStatusEnums::NOT_ACTUAL)
                <x-process-blocks.purchase-print.general :printOrder="$printOrder"/>
                <x-process-blocks.purchase-print.payment :printOrder="$printOrder"/>
                <x-process-blocks.purchase-print.tracking :printOrder="$printOrder"/>
            @else
                <div class="flex flex-col py-8 px-4 gap-4">
                    <span
                        class="text-dark-300 italic text-2xl text-center">Заявка на продвижение в статусе "Неактуальна".</span>
                </div>
            @endif
        </div>

</div>
