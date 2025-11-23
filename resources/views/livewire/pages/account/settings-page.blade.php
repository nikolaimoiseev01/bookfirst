<div x-data="{showEdit: $wire.entangle('showEdit')}" class="flex flex-col">
    @section('title')
        Настройки
    @endsection
    <div class="flex gap-8 sm:flex-col sm:text-center">
        <div x-show="!showEdit" class="flex flex-col gap-2">
            <p>Имя: {{$user['name']}}</p>
            <p>Фамилия: {{$user['surname']}}</p>
            <p>Псевдоним: {{$user['nickname']}}</p>
            <p>Email: {{$user['email']}}</p>
        </div>
        <img src="{{getUserAvatar($user)}}" x-show="!showEdit" class="h-32 w-32 rounded-full sm:mx-auto"
             alt="">
    </div>

    <form x-show="showEdit" class="flex flex-col gap-2 w-full max-w-lg mb-2">
        <x-ui.input.text label="Имя" wire:model="name"/>
        <x-ui.input.text label="Фамилия" wire:model="surname"/>
        <x-ui.input.text label="Псевдоним" wire:model="nickname"/>
        <x-filepond::upload
            placeholder="Новый автатар: <span class='filepond--label-action'> загрузить </span> или переместить файл в это окно"
            wire:model="file"/>
        <x-ui.link-simple wire:click="update">Сохранить</x-ui.link-simple>
    </form>

    <div class="flex flex-col gap-4 mt-4">
        <x-ui.link-simple @click="showEdit = !showEdit" class="sm:mx-auto"
                          x-text="showEdit ? 'Отменить' : 'Редактировать профиль'"/>
        <x-ui.link wire:click="logout" class="mt-8 w-fit sm:mx-auto">Выйти</x-ui.link>
    </div>


</div>
