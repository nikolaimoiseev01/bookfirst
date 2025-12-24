<?php

namespace App\Filament\Widgets;

use App\Models\OwnBook\OwnBook;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class OwnBookChart extends ChartWidget
{
    protected ?string $heading = 'Книги по источнику (UTM)';
    protected ?string $maxHeight = '400px';

    protected function getData(): array
    {
        $data = OwnBook::query()
            ->join('users', 'users.id', '=', 'own_books.user_id')
            ->selectRaw("
            CASE
                WHEN users.reg_utm_source LIKE 'CHIT%' THEN 'Читальня'
                WHEN users.reg_utm_source LIKE 'EMAIL%' THEN 'Email'
                WHEN users.reg_utm_source IS NULL THEN 'Без UTM'
                ELSE users.reg_utm_source
            END as label,
            COUNT(*) as total
        ")
            ->groupBy('label')
            ->orderByDesc('total')
            ->get();

        $labels = $data->pluck('label');
        $values = $data->pluck('total');

        // 🎨 палитра
        $colors = [
            '#3b82f6', // blue
            '#22c55e', // green
            '#f97316', // orange
            '#a855f7', // purple
            '#ef4444', // red
            '#14b8a6', // teal
            '#eab308', // yellow
            '#6366f1', // indigo
            '#ec4899', // pink
        ];

        return [
            'datasets' => [
                [
                    'data' => $values,
                    'backgroundColor' => $labels->map(
                        fn($_, $i) => $colors[$i % count($colors)]
                    ),
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }

    protected function getOptions(): array
    {
        return [
            'elements' => [
                'arc' => [
                    'borderWidth' => 0, // ❌ убираем обводку сегментов
                ],
            ],
            'plugins' => [
                'legend' => [
                    'position' => 'right',
                ],
            ],
        ];
    }
}
