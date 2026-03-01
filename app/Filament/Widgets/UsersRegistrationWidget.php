<?php

namespace App\Filament\Widgets;

use App\Models\User\User;
use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\ChartWidget\Concerns\HasFiltersSchema;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Illuminate\Support\Carbon;

class UsersRegistrationWidget extends ChartWidget
{
    use HasFiltersSchema;
    use HasWidgetShield;

//    protected ?string $maxHeight = '600px';
    protected ?string $heading = 'Пользователи по дате создания';
    protected bool $isCollapsible = true;
    public $start;
    public $end;

    public function filtersSchema(Schema $schema): Schema
    {
        return $schema->components([
            DatePicker::make('startDate')
                ->default(now()->subDays(7)),
            DatePicker::make('endDate')
                ->default(now()),
            Select::make('dimension')
                ->options([
                    'total' => 'Всего',
                    'reg_utm_source' => 'UTM Source',
                    'reg_utm_medium' => 'UTM Medium',
                    'reg_type' => 'Тип регистрации',
                ])
                ->default('reg_utm_source'),
            Select::make('grouping')
                ->options([
                    'day' => 'По дням',
                    'week' => 'По неделям',
                    'month' => 'По месяца'
                ])
                ->default('day')
        ])->columns(2);
    }

    protected function getData(): array
    {
        $dimension = $this->filters['dimension'] ?? 'reg_utm_source';
        $groupBy = $this->filters['grouping'] ?? 'day';
        $startDate = $this->filters['startDate'] ?? now()->subDays(7);
        $endDate = $this->filters['endDate'] ?? now();

        $this->start = $startDate
            ? Carbon::parse($startDate)->startOfDay()
            : now()->subDay()->startOfHour();

        $this->end = $endDate
            ? Carbon::parse($endDate)->endOfDay()
            : now()->endOfHour();

        $data = Trend::model(User::class)
            ->between(start: $this->start, end: $this->end);

        match ($groupBy) {
            'day' => $data->perDay(),
            'week' => $data->perWeek(),
            'month' => $data->perMonth()
        };

        // 🔹 Dimension
        if ($dimension === 'total') {
            $data = $data->count();

            return [
                'datasets' => [
                    [
                        'label' => 'Пользователей',
                        'data' => $data->pluck('aggregate'),
                    ],
                ],
                'labels' => $data->pluck('date'),
            ];
        }

        // 🔹 Group by dimension (utm, reg_type)
        return $this->buildDimensionChart($data, $dimension);
    }


    protected function buildDimensionChart(Trend $trend, string $dimension): array
    {
        // 🎨 Палитра (можно расширять)
        $colors = [
            '#3b82f6', // blue
            '#22c55e', // green
            '#f97316', // orange
            '#a855f7', // purple
            '#ef4444', // red
            '#64748b', // gray (other)
        ];

        // 1️⃣ Топ-5 значений по количеству
        $topValues = User::query()
            ->selectRaw("$dimension, COUNT(*) as total")
            ->whereNotNull($dimension)
            ->groupBy($dimension)
            ->orderByDesc('total')
            ->limit(5)
            ->pluck($dimension)
            ->toArray();

        // 2️⃣ Все остальные → other
        $otherQuery = User::query()
            ->whereNotNull($dimension)
            ->whereNotIn($dimension, $topValues);

        $datasets = [];
        $labels = null;
        $colorIndex = 0;

        // 3️⃣ Линии для топ-5
        foreach ($topValues as $value) {
            $query = User::query()->where($dimension, $value);

            $data = Trend::query($query)
                ->between(start: $this->start, end: $this->end);

            match ($this->filters['grouping'] ?? 'day') {
                'week'  => $data->perWeek(),
                'month' => $data->perMonth(),
                default => $data->perDay(),
            };

            $data = $data->count();

            $datasets[] = [
                'label' => (string) $value,
                'data' => $data->pluck('aggregate'),
                'borderColor' => $colors[$colorIndex],
                'backgroundColor' => $colors[$colorIndex],
                'fill' => false,
            ];

            $labels ??= $data->pluck('date');
            $colorIndex++;
        }

        // 4️⃣ Линия OTHER
        if ($otherQuery->exists()) {
            $otherTrend = Trend::query($otherQuery)
                ->between(start: $this->start, end: $this->end);

            match ($this->filters['grouping'] ?? 'day') {
                'week'  => $otherTrend->perWeek(),
                'month' => $otherTrend->perMonth(),
                default => $otherTrend->perDay(),
            };

            $otherData = $otherTrend->count();
            $color = $colors[$colorIndex];
            $datasets[] = [
                'label' => 'Other',
                'data'  => $otherData->pluck('aggregate'),

                'borderColor' => $color,
                'backgroundColor' => $color,

                'pointBackgroundColor' => $color,
                'pointBorderColor' => '#ffffff',

                'pointHoverBackgroundColor' => $color,
                'pointHoverBorderColor' => $color,
                'hoverBorderColor' => $color,
                'hoverBackgroundColor' => $color,

                'borderDash' => [6, 4],
                'fill' => false,
            ];

            $labels ??= $otherData->pluck('date');
        }

        return [
            'datasets' => $datasets,
            'labels' => $labels,
        ];
    }



    protected function getType(): string
    {
        return 'line';
    }



    protected function getOptions(): array
    {
        return [
            'interaction' => [
                'mode' => 'nearest',
                'intersect' => true,
            ],
        ];
    }
}
