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

<div class="flex items-center gap-2">
    <div class="relative w-8 h-8 rounded-full flex-shrink-0"
         style="background: conic-gradient(
             {{ $color }} 0% {{ $percent }}%,
             #e5e7eb {{ $percent }}% 100%
         );">
        <div class="absolute inset-1 bg-white rounded-full"></div>
    </div>
    <span class="text-sm font-medium">{{ $percent }}%</span>
</div>
