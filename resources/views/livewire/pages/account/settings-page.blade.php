<div x-data="{showEdit: false}" class="flex gap-4">
    @section('title')
        Настройки
    @endsection
    <div class="flex flex-col">
        <div x-show="!showEdit" class="flex flex-col gap-2">
            <p>Имя: {{$user['name']}}</p>
            <p>Фамилия: {{$user['surname']}}</p>
            <p>Псевдоним: {{$user['nickname']}}</p>
            <p>Email: {{$user['email']}}</p>
        </div>
        <form x-show="showEdit"  class="flex flex-col gap-2">
            <x-ui.input.text label="Имя" wire:model="name"/>
            <x-ui.input.text label="Фамилия" wire:model="surname"/>
            <x-ui.input.text label="Псевдоним" wire:model="nickname"/>
            <x-ui.link-simple wire:click="update">Сохранить</x-ui.link-simple>
        </form>
        <x-ui.link-simple @click="showEdit = !showEdit" x-text="showEdit ? 'Отменить' : 'Редактировать'"/>
    </div>

        <div class="flex flex-col">

        </div>

    <x-ui.link wire:click="logout" class="mt-8 w-fit">Выйти</x-ui.link>
</div>
