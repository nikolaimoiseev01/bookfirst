<div
    x-data="{
        editing: false,
        status: '{{ $chat->status }}',
        originalStatus: '{{ $chat->status }}',
        save() {
            console.log(123)
            this.$wire.changeStatus(this.status)
            this.originalStatus = this.status
            this.editing = false
        },
        cancel() {
            this.status = this.originalStatus
            this.editing = false
        }
    }"
    class="flex items-center gap-2 mb-4"
>

    <!-- VIEW -->
    <template x-if="!editing">
        <div class="flex items-center gap-2">
            <x-filament::icon-button
                icon="heroicon-o-pencil-square"
                tooltip="Поменять статус"
                @click="editing = true"
            />
            <p class="text-sm">
                Статус чата:
                <span class="font-medium">
                    {{ $chat->status->value }}
                </span>
            </p>
        </div>
    </template>

    <!-- EDIT -->
    <template x-if="editing">
        <div class="flex items-center gap-2">
            <x-filament::icon-button
                icon="heroicon-o-check"
                @click="save"
                tooltip="Сохранить выбранный статус"
            />

            <x-filament::icon-button
                icon="heroicon-o-x-mark"
                tooltip="Назад"
                @click="editing = false"
            />
            <select
                x-model="status"
                class="border rounded px-2 py-1 text-sm"
            >
                @foreach ($statuses as $status)
                    <option value="{{ $status->value }}">
                        {{ $status->value }}
                    </option>
                @endforeach
            </select>

        </div>
    </template>
    <x-filament::icon-button
        icon="heroicon-o-check-badge"
        tooltip="Ответ получен"
        color="success"
        wire:click="changeStatus('{{\App\Enums\ChatStatusEnums::ANSWERED}}')"
        label="Ответ получен"
    />
    <x-filament::icon-button
        icon="heroicon-o-x-circle"
        tooltip="Чат закрыт"
        color="danger"
        wire:click="changeStatus('{{\App\Enums\ChatStatusEnums::CLOSED}}')"
        label="Ответ получен"
    />
</div>
