<div>
    @section('title')
        Подтвердите Email
    @endsection
    <div class="border border-green-500 rounded-2xl p-4 flex flex-col gap-4 max-w-3xl">
        <p>Мы отправили ссылку на почту: <b>{{\Illuminate\Support\Facades\Auth::user()->email}}</b><br>
            Пожалуйста, перейдите по ней, чтобы подтвердить Ваш Email.</p>
        <div class="flex justify-between items-center">
            <div class="flex flex-col">
                <p class="text-xl">Не получили письмо?</p>
                <x-ui.link-simple wire:click="sendVerification()" class="text-xl">Запросить еще одно</x-ui.link-simple>
            </div>
            <x-ui.button wire:click="logout()">Выйти</x-ui.button>
        </div>
        @if (session('status'))
            <p class="text-green-500"><b>{{ __('Новое письмо было отправлено на указанный Email!') }}</b></p>
        @endif
    </div>
</div>
