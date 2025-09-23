<form  x-data="{
            needPrint: $wire.entangle('needPrint'),
            needCheck: $wire.entangle('needCheck')
        }"
       wire:submit="saveApplication()" class="mb-16 max-w-5xl">
    @section('title')
        Новая заявка в сборник {{$collection['title']}}
    @endsection

    <div class="flex container p-8 gap-16">

        <div class="flex flex-col gap-4 flex-1">
            <div class="flex gap-4">
                <x-ui.input.text name="name" label="Имя*" wire:model="name"/>
                <x-ui.input.text name="surname" label="Фамилия*" wire:model="surname"/>
                <x-ui.input.text name="nickname" label="Псевдоним*" wire:model="nickname"/>
            </div>
            <div class="flex flex-col gap-2">
                <p>Произведения для участия*</p>
                <x-ui.work-choose :userWorks="$userWorks"/>
            </div>
            <div class="flex gap-4">
                <div class="flex gap-2 items-center">
                    <label for="needPrint">Необходимы печатные экземпляры</label>
                    <x-ui.question-mark>Электронный вариант доступен каждому участнику</x-ui.question-mark>
                    <x-ui.input.checkbox wire:model="needPrint" id="needPrint" label=""/>
                </div>

                <div class="flex gap-2 items-center">
                    <label for="needPrint">Нужна проверка</label>
                    <x-ui.question-mark>Услуга проверки пунктуации и орфографии</x-ui.question-mark>
                    <x-ui.input.checkbox wire:model="needCheck" id="needCheck" label=""/>
                </div>
            </div>

            <div x-show="needPrint"
                 x-cloak
                 x-collapse.duration.800ms>
                <x-ui.address-choose/>
            </div>


        </div>

        <div class="flex flex-col gap-2 justify-evenly items-center flex-wrap">
            <x-price-element price="3000" label="Участие"/>
            <span class="text-2xl text-dark-200">+</span>
            <x-price-element price="3000" label="Печать (42 экз.)"/>
            <span class="text-2xl text-dark-200">+</span>
            <x-price-element price="3000" label="Проверка"/>
        </div>
    </div>

    <x-ui.button>Отправить заявку</x-ui.button>

</form>
