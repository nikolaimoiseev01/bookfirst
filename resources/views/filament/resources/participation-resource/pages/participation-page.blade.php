<x-filament::page>
<h1 class="filament-header-heading text-2xl font-bold tracking-tight">Страница автора {{$participation['name']}}</h1>

    <div class="grid grid-cols-1      filament-forms-component-container gap-6">
        <div class="col-span-full">
            <div>
                <div class="grid grid-cols-1   lg:grid-cols-2   filament-forms-component-container gap-6">
                    <div class="col-span-full">
                        <div>
                            <div class="grid grid-cols-1   lg:grid-cols-1   filament-forms-component-container gap-6">
                                <div class="col-span-1">
                                    <div x-data="{
        tab: null,

        init: function () {
            this.$watch('tab', () => this.updateQueryString())

            this.tab = this.getTabs()[1 - 1]
        },

        getTabs: function () {
            return JSON.parse(this.$refs.tabsData.value)
        },

        updateQueryString: function () {
            if (! false) {
                return
            }

            const url = new URL(window.location.href)
            url.searchParams.set(null, this.tab)

            history.pushState(null, document.title, url.toString())
        },
    }" class="filament-forms-tabs-component rounded-xl border border-gray-300 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800" wire:key="LsaGDS6XRBAM3y61HgJP.data.Filament\Forms\Components\Tabs.container" wire:ignore.self="">
                                        <input type="hidden" value="[&quot;-obshhaia-informaciia-tab&quot;,&quot;-proizvedeniia-tab&quot;,&quot;-label-3-tab&quot;]" x-ref="tabsData">

                                        <div aria-label="Общая информация" role="tablist" class="filament-forms-tabs-component-header flex overflow-y-auto rounded-t-xl bg-gray-100 dark:bg-gray-700">

                                            <button type="button" aria-controls="-obshhaia-informaciia-tab" x-bind:aria-selected="tab === '-obshhaia-informaciia-tab'" x-on:click="tab = '-obshhaia-informaciia-tab'" role="tab" x-bind:tabindex="tab === '-obshhaia-informaciia-tab' ? 0 : -1" class="filament-forms-tabs-component-button flex shrink-0 items-center gap-2 p-3 text-sm font-medium filament-forms-tabs-component-button-active bg-white text-primary-600 dark:bg-gray-800" x-bind:class="{
                    'text-gray-500 hover:text-gray-800 focus:text-primary-600  dark:text-gray-400 dark:hover:text-gray-200 dark:focus:text-primary-600 ': tab !== '-obshhaia-informaciia-tab',
                    'filament-forms-tabs-component-button-active bg-white text-primary-600  dark:bg-gray-800 ': tab === '-obshhaia-informaciia-tab',
                }" aria-selected="true" tabindex="0">

                                                <span>Общая информация</span>


                                            </button>

                                            <button type="button" aria-controls="-proizvedeniia-tab" x-bind:aria-selected="tab === '-proizvedeniia-tab'" x-on:click="tab = '-proizvedeniia-tab'" role="tab" x-bind:tabindex="tab === '-proizvedeniia-tab' ? 0 : -1" class="filament-forms-tabs-component-button flex shrink-0 items-center gap-2 p-3 text-sm font-medium text-gray-500 hover:text-gray-800 focus:text-primary-600 dark:text-gray-400 dark:hover:text-gray-200 dark:focus:text-primary-600" x-bind:class="{
                    'text-gray-500 hover:text-gray-800 focus:text-primary-600  dark:text-gray-400 dark:hover:text-gray-200 dark:focus:text-primary-600 ': tab !== '-proizvedeniia-tab',
                    'filament-forms-tabs-component-button-active bg-white text-primary-600  dark:bg-gray-800 ': tab === '-proizvedeniia-tab',
                }" aria-selected="false" tabindex="-1">

                                                <span>Произведения</span>


                                            </button>

                                            <button type="button" aria-controls="-label-3-tab" x-bind:aria-selected="tab === '-label-3-tab'" x-on:click="tab = '-label-3-tab'" role="tab" x-bind:tabindex="tab === '-label-3-tab' ? 0 : -1" class="filament-forms-tabs-component-button flex shrink-0 items-center gap-2 p-3 text-sm font-medium text-gray-500 hover:text-gray-800 focus:text-primary-600 dark:text-gray-400 dark:hover:text-gray-200 dark:focus:text-primary-600" x-bind:class="{
                    'text-gray-500 hover:text-gray-800 focus:text-primary-600  dark:text-gray-400 dark:hover:text-gray-200 dark:focus:text-primary-600 ': tab !== '-label-3-tab',
                    'filament-forms-tabs-component-button-active bg-white text-primary-600  dark:bg-gray-800 ': tab === '-label-3-tab',
                }" aria-selected="false" tabindex="-1">

                                                <span>Label 3</span>


                                            </button>
                                        </div>

                                        <div aria-labelledby="-obshhaia-informaciia-tab" id="-obshhaia-informaciia-tab" role="tabpanel" tabindex="0" x-bind:class="{
        'invisible h-0 p-0 overflow-y-hidden': tab !== '-obshhaia-informaciia-tab',
        'p-6': tab === '-obshhaia-informaciia-tab',
    }" x-on:expand-concealing-component.window="
        error = $el.querySelector('[data-validation-error]')

        if (! error) {
            return
        }

        tab = '-obshhaia-informaciia-tab'
        if (document.body.querySelector('[data-validation-error]') !== error) {
            return
        }

        setTimeout(
            () =>
                $el.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start',
                    inline: 'start',
                }),
            200,
        )
    " class="filament-forms-tabs-component-tab outline-none p-6" wire:key="LsaGDS6XRBAM3y61HgJP.data.Filament\Forms\Components\Tab.tabs.-obshhaia-informaciia-tab">
                                            <div class="grid grid-cols-1      filament-forms-component-container gap-6">
                                                <div class="col-span-1" wire:key="LsaGDS6XRBAM3y61HgJP.data.name.Filament\Forms\Components\TextInput">
                                                    <div class="filament-forms-field-wrapper">

                                                        <div class="space-y-2">
                                                            <div class="flex items-center justify-between space-x-2 rtl:space-x-reverse">
                                                                <label class="filament-forms-field-wrapper-label inline-flex items-center space-x-3 rtl:space-x-reverse" for="data.name">


    <span class="text-sm font-medium leading-4 text-gray-700 dark:text-gray-300">

        Name    </span>


                                                                </label>

                                                            </div>

                                                            <div class="filament-forms-text-input-component group flex items-center space-x-2 rtl:space-x-reverse">



                                                                <div class="flex-1">
                                                                    <input x-data="{}" wire:model.defer="data.name" type="text" dusk="filament.forms.data.name" disabled="" id="data.name" class="filament-forms-input block w-full rounded-lg shadow-sm outline-none transition duration-75 focus:ring-1 focus:ring-inset disabled:opacity-70 dark:bg-gray-700 dark:text-white border-gray-300 focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:focus:border-primary-500" x-bind:class="{
                    'border-gray-300 focus:border-primary-500 focus:ring-primary-500': ! (
                        'data.name' in $wire.__instance.serverMemo.errors
                    ),
                    'dark:border-gray-600 dark:focus:border-primary-500':
                        ! ('data.name' in $wire.__instance.serverMemo.errors) &amp;&amp; true,
                    'border-danger-600 ring-danger-600 focus:border-danger-500 focus:ring-danger-500':
                        'data.name' in $wire.__instance.serverMemo.errors,
                    'dark:border-danger-400 dark:ring-danger-400 dark:focus:border-danger-500 dark:focus:ring-danger-500':
                        'data.name' in $wire.__instance.serverMemo.errors &amp;&amp; true,
                }">
                                                                </div>



                                                            </div>


                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-span-1" wire:key="LsaGDS6XRBAM3y61HgJP.data.surname.Filament\Forms\Components\TextInput">
                                                    <div class="filament-forms-field-wrapper">

                                                        <div class="space-y-2">
                                                            <div class="flex items-center justify-between space-x-2 rtl:space-x-reverse">
                                                                <label class="filament-forms-field-wrapper-label inline-flex items-center space-x-3 rtl:space-x-reverse" for="data.surname">


    <span class="text-sm font-medium leading-4 text-gray-700 dark:text-gray-300">

        Surname    </span>


                                                                </label>

                                                            </div>

                                                            <div class="filament-forms-text-input-component group flex items-center space-x-2 rtl:space-x-reverse">



                                                                <div class="flex-1">
                                                                    <input x-data="{}" wire:model.defer="data.surname" type="text" dusk="filament.forms.data.surname" disabled="" id="data.surname" class="filament-forms-input block w-full rounded-lg shadow-sm outline-none transition duration-75 focus:ring-1 focus:ring-inset disabled:opacity-70 dark:bg-gray-700 dark:text-white border-gray-300 focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:focus:border-primary-500" x-bind:class="{
                    'border-gray-300 focus:border-primary-500 focus:ring-primary-500': ! (
                        'data.surname' in $wire.__instance.serverMemo.errors
                    ),
                    'dark:border-gray-600 dark:focus:border-primary-500':
                        ! ('data.surname' in $wire.__instance.serverMemo.errors) &amp;&amp; true,
                    'border-danger-600 ring-danger-600 focus:border-danger-500 focus:ring-danger-500':
                        'data.surname' in $wire.__instance.serverMemo.errors,
                    'dark:border-danger-400 dark:ring-danger-400 dark:focus:border-danger-500 dark:focus:ring-danger-500':
                        'data.surname' in $wire.__instance.serverMemo.errors &amp;&amp; true,
                }">
                                                                </div>



                                                            </div>


                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-span-1" wire:key="LsaGDS6XRBAM3y61HgJP.data.nickname.Filament\Forms\Components\TextInput">
                                                    <div class="filament-forms-field-wrapper">

                                                        <div class="space-y-2">
                                                            <div class="flex items-center justify-between space-x-2 rtl:space-x-reverse">
                                                                <label class="filament-forms-field-wrapper-label inline-flex items-center space-x-3 rtl:space-x-reverse" for="data.nickname">


    <span class="text-sm font-medium leading-4 text-gray-700 dark:text-gray-300">

        Nickname    </span>


                                                                </label>

                                                            </div>

                                                            <div class="filament-forms-text-input-component group flex items-center space-x-2 rtl:space-x-reverse">



                                                                <div class="flex-1">
                                                                    <input x-data="{}" wire:model.defer="data.nickname" type="text" dusk="filament.forms.data.nickname" disabled="" id="data.nickname" class="filament-forms-input block w-full rounded-lg shadow-sm outline-none transition duration-75 focus:ring-1 focus:ring-inset disabled:opacity-70 dark:bg-gray-700 dark:text-white border-gray-300 focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:focus:border-primary-500" x-bind:class="{
                    'border-gray-300 focus:border-primary-500 focus:ring-primary-500': ! (
                        'data.nickname' in $wire.__instance.serverMemo.errors
                    ),
                    'dark:border-gray-600 dark:focus:border-primary-500':
                        ! ('data.nickname' in $wire.__instance.serverMemo.errors) &amp;&amp; true,
                    'border-danger-600 ring-danger-600 focus:border-danger-500 focus:ring-danger-500':
                        'data.nickname' in $wire.__instance.serverMemo.errors,
                    'dark:border-danger-400 dark:ring-danger-400 dark:focus:border-danger-500 dark:focus:ring-danger-500':
                        'data.nickname' in $wire.__instance.serverMemo.errors &amp;&amp; true,
                }">
                                                                </div>



                                                            </div>


                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>

                                        <div aria-labelledby="-proizvedeniia-tab" id="-proizvedeniia-tab" role="tabpanel" tabindex="0" x-bind:class="{
        'invisible h-0 p-0 overflow-y-hidden': tab !== '-proizvedeniia-tab',
        'p-6': tab === '-proizvedeniia-tab',
    }" x-on:expand-concealing-component.window="
        error = $el.querySelector('[data-validation-error]')

        if (! error) {
            return
        }

        tab = '-proizvedeniia-tab'
        if (document.body.querySelector('[data-validation-error]') !== error) {
            return
        }

        setTimeout(
            () =>
                $el.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start',
                    inline: 'start',
                }),
            200,
        )
    " class="filament-forms-tabs-component-tab outline-none invisible h-0 p-0 overflow-y-hidden" wire:key="LsaGDS6XRBAM3y61HgJP.data.Filament\Forms\Components\Tab.tabs.-proizvedeniia-tab">
                                            <div class="grid grid-cols-1      filament-forms-component-container gap-6">

                                            </div>

                                        </div>

                                        <div aria-labelledby="-label-3-tab" id="-label-3-tab" role="tabpanel" tabindex="0" x-bind:class="{
        'invisible h-0 p-0 overflow-y-hidden': tab !== '-label-3-tab',
        'p-6': tab === '-label-3-tab',
    }" x-on:expand-concealing-component.window="
        error = $el.querySelector('[data-validation-error]')

        if (! error) {
            return
        }

        tab = '-label-3-tab'
        if (document.body.querySelector('[data-validation-error]') !== error) {
            return
        }

        setTimeout(
            () =>
                $el.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start',
                    inline: 'start',
                }),
            200,
        )
    " class="filament-forms-tabs-component-tab outline-none invisible h-0 p-0 overflow-y-hidden" wire:key="LsaGDS6XRBAM3y61HgJP.data.Filament\Forms\Components\Tab.tabs.-label-3-tab">
                                            <div class="grid grid-cols-1      filament-forms-component-container gap-6">

                                            </div>

                                        </div>

                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-filament::page>
