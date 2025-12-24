<?php

namespace App\Filament\Widgets;

use App\Models\Collection\Participation;
use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\ChartWidget\Concerns\HasFiltersSchema;
use Illuminate\Support\Carbon;

class ParticipationChart extends ChartWidget
{
    use HasFiltersSchema;
    use HasWidgetShield;

    protected ?string $heading = 'Участия';
    protected bool $isCollapsible = true;

    protected Carbon $start;
    protected Carbon $end;

    public function filtersSchema(Schema $schema): Schema
    {
        return $schema->components([
            DatePicker::make('startDate')
                ->default(now()->subDays(365)),
            DatePicker::make('endDate')
                ->default(now()),
            Select::make('dimension')
                ->options([
                    'total' => 'Всего',
                    'COALESCE(promocodes.group, \'Без промокода\')' => 'По группам промокодов',
                    'COALESCE(promocodes.name, \'Без промокода\')' => 'По промокодам',
                    'COALESCE(users.reg_utm_source, \'Без промокода\')' => 'По utm_source',
                ])
                ->default('COALESCE(promocodes.group, \'Без промокода\')'),
            Select::make('aggregation')
                ->options([
                    'COUNT(*)' => 'Количество',
                    'SUM(participations.price_total)' => 'Сумма',
                ])
                ->default('COUNT(*)'),
        ])->columns(2);
    }

    protected function getData(): array
    {
        $dimension = $this->filters['dimension'];
        $aggregation = $this->filters['aggregation'];

        $this->start = Carbon::parse($this->filters['startDate'])->startOfDay();
        $this->end   = Carbon::parse($this->filters['endDate'])->endOfDay();

        $baseQuery = Participation::query()
            ->join('collections', 'collections.id', '=', 'participations.collection_id')
            ->whereBetween('participations.created_at', [$this->start, $this->end]);

        /**
         * ======================
         * TOTAL
         * ======================
         */
        if ($dimension === 'total') {
            $data = (clone $baseQuery)
                ->selectRaw("
                    collections.title_short as label,
                    {$aggregation} as total
                ")
                ->groupBy('collections.id', 'label')
                ->orderBy('collections.created_at')
                ->get();

            return [
                'datasets' => [
                    [
                        'label' => $aggregation == 'COUNT(*)' ? 'Участий' : 'Выручка',
                        'data' => $data->pluck('total'),
                        'backgroundColor' => '#3b82f6',
                    ],
                ],
                'labels' => $data->pluck('label'),
            ];
        }

        /**
         * ======================
         * PROMOCODE (STACKED)
         * ======================
         */
        $data = (clone $baseQuery)
            ->leftJoin('promocodes', 'promocodes.id', '=', 'participations.promocode_id')
            ->leftJoin('users', 'users.id', '=', 'participations.user_id')
            ->selectRaw("
                collections.title_short as collection,
                {$dimension} as segment,
                {$aggregation} as total
            ")
            ->groupBy('collections.id', 'collection', 'segment')
            ->orderBy('collections.created_at')
            ->get();

        $labels = $data->pluck('collection')->unique()->values();

        $segments = $data
            ->pluck('segment')
            ->unique()
            ->sort()
            ->values();

        // 🎨 Палитра
        $palette = [
            '#3b82f6', '#22c55e', '#f97316', '#a855f7',
            '#ef4444', '#14b8a6', '#eab308', '#6366f1',
            '#ec4899',
        ];

        $datasets = [];
        $colorIndex = 0;

        foreach ($segments as $segment) {
            $color = $palette[$colorIndex % count($palette)];

            $datasets[] = [
                'label' => $segment,
                'data' => $labels->map(fn ($label) =>
                    $data
                        ->where('collection', $label)
                        ->where('segment', $segment)
                        ->first()
                        ->total ?? 0
                ),

                // 🎨 цвета
                'backgroundColor' => $this->withAlpha($color, 0.85),
                'borderWidth' => 0,
            ];

            $colorIndex++;
        }

        return [
            'datasets' => $datasets,
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getOptions(): array
    {
        return [
            'scales' => [
                'x' => ['stacked' => true],
                'y' => ['stacked' => true],
            ],
            'elements' => [
                'bar' => [
                    'borderWidth' => 0,
                    'hoverBorderWidth' => 0,
                ],
            ],
        ];
    }

    /**
     * 🎨 HEX → RGBA
     */
    protected function withAlpha(string $hex, float $alpha): string
    {
        $hex = ltrim($hex, '#');

        [$r, $g, $b] = [
            hexdec(substr($hex, 0, 2)),
            hexdec(substr($hex, 2, 2)),
            hexdec(substr($hex, 4, 2)),
        ];

        return "rgba($r, $g, $b, $alpha)";
    }
}
