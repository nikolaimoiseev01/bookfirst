<x-filament::page>
    @livewire(\App\Filament\Widgets\UsersRegistrationWidget::class)
{{--    <div class="rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10"--}}
{{--         x-data="{ tab: 'users' }">--}}
{{--        <x-filament::tabs label="Content tabs" contained="true">--}}
{{--            <x-filament::tabs.item @click="tab = 'users'" :alpine-active="'tab === \'users\''">--}}
{{--                Пользователи--}}
{{--            </x-filament::tabs.item>--}}

{{--            <x-filament::tabs.item @click="tab = 'tab2'" :alpine-active="'tab === \'tab2\''">--}}
{{--                Tab 2--}}
{{--            </x-filament::tabs.item>--}}

{{--        </x-filament::tabs>--}}

{{--        <div class="fi-fo-tabs-tab p-6 max-h-80">--}}
{{--            <div x-show="tab === 'users'">--}}
{{--                @livewire(\App\Filament\Widgets\UsersRegistrationWidget::class)--}}
{{--            </div>--}}

{{--            <div x-show="tab === 'tab2'">--}}
{{--                content 2...--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}

</x-filament::page>
