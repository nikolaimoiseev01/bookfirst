<x-participation-blocks.template status="green" title="Моя заявка">
    <div class="flex flex-col p-4" x-data="{showWorks: false}">
        <div class="flex justify-between gap-4">
            <div class="flex flex-col w-1/2">
                <p><b>Имя в сборнике: </b>{{$participation['author_name']}}</p>
                <div class="flex gap-4 items-center">
                    <p><b>Произведений: </b>{{count($participation->participationWorks)}}</p>
                    <span class="text-green-400 text-xl cursor-pointer" x-text="showWorks ? 'Свернуть' : 'Показать'"
                          @click="showWorks = !showWorks"></span>
                </div>
                <p>Строчек: {{$participation['rows']}}, Сраниц: {{$participation['pages']}}</p>
            </div>
            <div class="flex flex-col w-1/2">
                @if($participation->printOrder ?? null)
                    <p class="font-semibold text-2xl">Печатные экземпляры</p>
                    <p><b>Фио получателя: </b>{{$participation->printOrder['receiver_name']}}</p>
                    <p><b>Телефон получателя: </b>{{$participation->printOrder['receiver_telephone']}}</p>
                    <p><b>Адрес получателя: </b>{{$participation->printOrder['address_json']['string']}}</p>
                @else
                    <p class="font-semibold text-2xl">Печатные экземпляры не требуются</p>
                @endif
            </div>
        </div>
        <div x-show="showWorks" x-collapse="">
            <div class="flex flex-col pt-4">
                <p class="font-semibold text-2xl mb-2">Произведения к участию</p>
                <div class="flex gap-4 flex-wrap ">
                    @foreach($participation->participationWorks as $work)
                        <a class="px-4 py-2 border rounded border-dark-100">{{$work->work['title']}}</a>
                    @endforeach
                </div>
            </div>
        </div>

    </div>

</x-participation-blocks.template>
