@php
    $statistic = $record->statistic()->latest()->first();
    if (!$statistic || $statistic->total == 0) {
        echo '-';
        return;
    }

    $total = $statistic->total;
    $value = $statistic->{$field};
    $percent = round(($value / $total) * 100, 1);
@endphp

<div class="flex items-center gap-2 mx-auto">
    <div class="relative w-8 h-8 rounded-full flex-shrink-0 group cursor-help"
         style="background: conic-gradient(
             {{ $color }} 0% {{ $percent }}%,
             #e5e7eb {{ $percent }}% 100%
         );">
        <div class="absolute inset-1 bg-white rounded-full flex items-center justify-center">
            <span class="text-[10px] font-semibold text-gray-700 text-center leading-[9px]">{{ $percent }}<br>%</span>
        </div>
        <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 px-2 py-1 bg-gray-900 text-white text-xs rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap pointer-events-none z-10">
            {{ $value }} из {{ $total }}
        </div>
    </div>
</div>
