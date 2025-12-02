<div>
    @if(!$checkNeedForSurvey)
        <div class="w-full border border-green-500 rounded-2xl p-4">
            @if($step == 0)
                <div class="flex gap-4 md:flex-col">
                    <p class="font-normal text-green-500">Пожалуйста, оцените процесс создания заявки</p>
                    <x-ui.input.stars/>
                    <x-ui.button class="flex-1" wire:click="sendSurvey()">Отправить</x-ui.button>
                </div>

            @elseif($step == 1)
                <div class="flex flex-col gap-4">
                    <x-ui.input.text-area model="text"
                                          description="Пожалуйста, опишите, что было не так. Каждый день мы стараемся быть лучше, поэтому нам важно это знать."
                                          :sendable="false"/>
                    <div class="flex ml-auto gap-8">
                        <x-ui.link-simple wire:click="$set('step', 0)">Назад</x-ui.link-simple>
                        <x-ui.button class="flex-1" wire:click="sendSurvey()">Отправить</x-ui.button>
                    </div>
                </div>
            @endif
        </div>
    @endif
</div>
