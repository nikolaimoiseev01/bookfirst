<div class="fi-section rounded-xl bg-white dark:bg-gray-900 shadow-sm ring-1 ring-gray-950/5 dark:ring-white/10 p-6">

    <div class="flex items-center justify-between mb-4">
        <h3 class="text-base font-semibold text-gray-950 dark:text-white">
            Распределение участий по количеству экземпляров
        </h3>

        @php
            $totalParticipations = array_sum($distribution);

            $totalBooks = collect($distribution)
                ->reduce(fn ($carry, $count, $booksCnt) => $carry + ($booksCnt * $count), 0);
        @endphp

        <div class="text-sm text-gray-500 dark:text-gray-400">
            Всего экземпляров: {{ ($totalBooks) }}
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead>
            <tr class="border-b border-gray-200 dark:border-gray-700">
                <th class="py-2 font-medium text-gray-600 dark:text-gray-300">
                    Кол-во экземпляров
                </th>
                <th class="py-2 font-medium text-gray-600 dark:text-gray-300 text-right">
                    Кол-во участий
                </th>
                <th class="py-2 font-medium text-gray-600 dark:text-gray-300 text-right">
                    %
                </th>
            </tr>
            </thead>
            <tbody>
            @php
                $total = array_sum($distribution);
            @endphp

            @foreach($distribution as $booksCnt => $count)
                @php
                    $percent = $total > 0 ? round(($count / $total) * 100, 1) : 0;
                @endphp

                <tr class=" border-b border-gray-100 cursor-pointer dark:border-gray-800 hover:bg-gray-50 dark:hover:bg-gray-800/40 transition">
                    <td class="py-2 font-medium text-gray-900 dark:text-white">
                        {{ $booksCnt }}
                    </td>

                    <td class="py-2 text-right text-gray-700 dark:text-gray-300">
                        {{ $count }}
                    </td>

                    <td class="py-2 text-right">
                        <div class="flex items-center justify-end gap-3">
                            <div class="w-24 bg-gray-200 dark:bg-gray-700 rounded-full h-2 overflow-hidden">
                                <div
                                    class="bg-primary-500 h-2 rounded-full transition-all duration-500"
                                    style="width: {{ $percent }}%"
                                ></div>
                            </div>
                            <span class="text-xs text-gray-600 dark:text-gray-400 w-10 text-right">
                                    {{ $percent }}%
                                </span>
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>

            <tfoot>
            <tr class="bg-gray-50 dark:bg-gray-800/50 font-semibold">
                <td class="py-2 text-gray-900 dark:text-white">
                    Итого
                </td>
                <td class="py-2 text-right text-gray-900 dark:text-white">
                    {{ $total }}
                </td>
                <td></td>
            </tr>
            </tfoot>
        </table>
    </div>
</div>
