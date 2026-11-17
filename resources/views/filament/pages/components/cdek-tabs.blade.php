<div class="mb-4 border-b border-gray-200 dark:border-white/10">
    <nav class="-mb-px flex gap-6" aria-label="Разделы отправок СДЭК">
        <a
            href="{{ \App\Filament\Pages\CdekShipmentHistory::getUrl() }}"
            @class([
                'border-b-2 px-1 pb-3 text-sm font-medium',
                'border-primary-600 text-primary-600 dark:border-primary-400 dark:text-primary-400' => $active === 'shipments',
                'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200' => $active !== 'shipments',
            ])
        >Отправления</a>
        <a
            href="{{ \App\Filament\Pages\CdekPickupGroups::getUrl() }}"
            @class([
                'border-b-2 px-1 pb-3 text-sm font-medium',
                'border-primary-600 text-primary-600 dark:border-primary-400 dark:text-primary-400' => $active === 'groups',
                'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200' => $active !== 'groups',
            ])
        >Группы заказов</a>
    </nav>
</div>
