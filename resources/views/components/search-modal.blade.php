<x-ui.modal name="searchModal">
    <div
        x-data="{
            search: '',
            error: '',
            submit() {
                if (this.search.trim().length < 3) {
                    this.error = 'Введите минимум 3 символа';
                    return;
                }

                this.error = '';
               Livewire.navigate(
                    `/search-result?search_request=${encodeURIComponent(this.search)}`
                );
            }
        }"
        class="flex flex-col gap-4 p-4"
    >
        <h3 class="text-xl font-semibold">Поиск</h3>

        <p class="text-gray-600">
            Введите более 3-х символов и мы постараемся найти это на нашем сайте
            (среди сборников, собственных книг, пользователей и произведений)
        </p>

        <x-ui.input.text
            x-model="search"
            placeholder="Введите запрос..."
        />

        <template x-if="error">
            <p class="text-red-500 text-sm" x-text="error"></p>
        </template>

        <button
            @click="submit"
            class="block text-green-500 border text-xl border-green-500 min-w-max gap-2 items-center justify-center rounded-lg py-1 px-8 cursor-pointer transition hover:bg-green-500 hover:text-white"
            :disabled="search.trim().length < 3"
        >
            Найти
        </button>
    </div>
</x-ui.modal>
