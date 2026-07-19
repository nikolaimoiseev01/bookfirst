@php
    $campaign = $record ?? $getRecord();
    $recipients = $campaign->campaignRecipients;
    $statistics = $campaign->statistic()->orderBy('created_at')->get();

    $stats = [
        'total_recipients' => $recipients->count(),
        'accepted' => $recipients->where('mailganer_status', 'accepted')->count(),
        'delivered' => $recipients->where('mailganer_status', 'delivered')->count(),
        'opened' => $recipients->where('mailganer_status', 'open')->count(),
        'clicked' => $recipients->where('mailganer_status', 'click')->count(),
        'failed' => $recipients->where('mailganer_status', 'failed')->count(),
        'fbl' => $recipients->where('mailganer_status', 'fbl')->count(),
        'unsubscribe' => $recipients->where('mailganer_status', 'unsubscribe')->count(),
        'duplicate' => $recipients->where('mailganer_status', 'duplicate')->count(),
    ];

    $stats['open_rate'] = $stats['delivered'] > 0 ? round(($stats['opened'] / $stats['delivered']) * 100, 1) : 0;
    $stats['click_rate'] = $stats['opened'] > 0 ? round(($stats['clicked'] / $stats['opened']) * 100, 1) : 0;

    // Prepare chart data from statistics
    $metricLabels = [
        'total' => 'Всего',
        'send_ok' => 'Отправлено',
        'send_fail' => 'Ошибки отправки',
        'open_msg' => 'Открытия',
        'open_msg_uniq' => 'Уникальные открытия',
        'click_link' => 'Клики',
        'click_link_uniq' => 'Уникальные клики',
        'gen_ok' => 'Сгенерировано',
        'dup' => 'Дубли',
        'bad' => 'Невалидные',
        'fbl' => 'Жалобы',
        'stop' => 'Стоп-лист',
        'trap' => 'Спам-ловушки',
        'bounce' => 'Bounce',
        'spam' => 'Спам',
        'unsubscribe' => 'Отписки',
    ];

    $metricColors = [
        'total' => 'rgb(99, 102, 241)',
        'send_ok' => 'rgb(34, 197, 94)',
        'send_fail' => 'rgb(239, 68, 68)',
        'open_msg' => 'rgb(168, 85, 247)',
        'open_msg_uniq' => 'rgb(139, 92, 246)',
        'click_link' => 'rgb(249, 115, 22)',
        'click_link_uniq' => 'rgb(234, 88, 12)',
        'gen_ok' => 'rgb(6, 182, 212)',
        'dup' => 'rgb(234, 179, 8)',
        'bad' => 'rgb(127, 29, 29)',
        'fbl' => 'rgb(220, 38, 38)',
        'stop' => 'rgb(107, 114, 128)',
        'trap' => 'rgb(75, 85, 99)',
        'bounce' => 'rgb(185, 28, 28)',
        'spam' => 'rgb(153, 27, 27)',
        'unsubscribe' => 'rgb(156, 163, 175)',
    ];

    $chartData = [
        'labels' => $statistics->map(fn($s) => $s->created_at->format('d.m H:i'))->toArray(),
        'datasets' => [],
    ];

    $metrics = ['total', 'send_ok', 'send_fail', 'open_msg', 'open_msg_uniq', 'click_link', 'click_link_uniq', 'gen_ok', 'dup', 'bad', 'fbl', 'stop', 'trap', 'bounce', 'spam', 'unsubscribe'];

    foreach ($metrics as $metric) {
        $values = $statistics->map(fn($s) => $s->$metric ?? 0)->toArray();
        $maxValue = max($values);

        if ($maxValue > 0) {
            $chartData['datasets'][] = [
                'label' => $metricLabels[$metric] ?? $metric,
                'data' => $values,
                'borderColor' => $metricColors[$metric] ?? 'rgb(99, 102, 241)',
                'backgroundColor' => str_replace('rgb', 'rgba', $metricColors[$metric] ?? 'rgb(99, 102, 241)') . ', 0.1)',
                'tension' => 0.4,
                'fill' => false,
            ];
        }
    }
@endphp

<div class="space-y-6">
    <!-- Stats Cards -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white dark:bg-gray-800 p-4 rounded-lg border border-gray-200 dark:border-gray-700">
            <div class="text-sm text-gray-500 dark:text-gray-400">Всего получателей</div>
            <div class="text-2xl font-bold">{{ $stats['total_recipients'] }}</div>
        </div>
        <div class="bg-white dark:bg-gray-800 p-4 rounded-lg border border-gray-200 dark:border-gray-700">
            <div class="text-sm text-gray-500 dark:text-gray-400">Принято</div>
            <div class="text-2xl font-bold text-blue-600">{{ $stats['accepted'] }}</div>
        </div>
        <div class="bg-white dark:bg-gray-800 p-4 rounded-lg border border-gray-200 dark:border-gray-700">
            <div class="text-sm text-gray-500 dark:text-gray-400">Доставлено</div>
            <div class="text-2xl font-bold text-green-600">{{ $stats['delivered'] }}</div>
        </div>
        <div class="bg-white dark:bg-gray-800 p-4 rounded-lg border border-gray-200 dark:border-gray-700">
            <div class="text-sm text-gray-500 dark:text-gray-400">Открыто</div>
            <div class="text-2xl font-bold text-purple-600">{{ $stats['opened'] }}</div>
        </div>
        <div class="bg-white dark:bg-gray-800 p-4 rounded-lg border border-gray-200 dark:border-gray-700">
            <div class="text-sm text-gray-500 dark:text-gray-400">Кликов</div>
            <div class="text-2xl font-bold text-orange-600">{{ $stats['clicked'] }}</div>
        </div>
        <div class="bg-white dark:bg-gray-800 p-4 rounded-lg border border-gray-200 dark:border-gray-700">
            <div class="text-sm text-gray-500 dark:text-gray-400">Ошибка</div>
            <div class="text-2xl font-bold text-red-600">{{ $stats['failed'] }}</div>
        </div>
        <div class="bg-white dark:bg-gray-800 p-4 rounded-lg border border-gray-200 dark:border-gray-700">
            <div class="text-sm text-gray-500 dark:text-gray-400">Жалоба</div>
            <div class="text-2xl font-bold text-red-500">{{ $stats['fbl'] }}</div>
        </div>
        <div class="bg-white dark:bg-gray-800 p-4 rounded-lg border border-gray-200 dark:border-gray-700">
            <div class="text-sm text-gray-500 dark:text-gray-400">Отписались</div>
            <div class="text-2xl font-bold text-gray-600">{{ $stats['unsubscribe'] }}</div>
        </div>
        <div class="bg-white dark:bg-gray-800 p-4 rounded-lg border border-gray-200 dark:border-gray-700">
            <div class="text-sm text-gray-500 dark:text-gray-400">Дубли</div>
            <div class="text-2xl font-bold text-yellow-600">{{ $stats['duplicate'] }}</div>
        </div>
        <div class="bg-white dark:bg-gray-800 p-4 rounded-lg border border-gray-200 dark:border-gray-700">
            <div class="text-sm text-gray-500 dark:text-gray-400">Open Rate</div>
            <div class="text-2xl font-bold">{{ $stats['open_rate'] }}%</div>
        </div>
        <div class="bg-white dark:bg-gray-800 p-4 rounded-lg border border-gray-200 dark:border-gray-700">
            <div class="text-sm text-gray-500 dark:text-gray-400">Click Rate</div>
            <div class="text-2xl font-bold">{{ $stats['click_rate'] }}%</div>
        </div>
    </div>

    @if(count($chartData['datasets']) > 0)
    <!-- Chart -->
    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg border border-gray-200 dark:border-gray-700">
        <h3 class="text-lg font-semibold mb-4">Динамика статистики</h3>
        <div class="h-96">
            <canvas id="statisticsChart"></canvas>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('statisticsChart').getContext('2d');

            const chartData = @json($chartData);

            new Chart(ctx, {
                type: 'line',
                data: chartData,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: {
                        mode: 'index',
                        intersect: false,
                    },
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top',
                        },
                        tooltip: {
                            mode: 'index',
                            intersect: false,
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return value;
                                }
                            }
                        },
                        x: {
                            ticks: {
                                maxRotation: 45,
                                minRotation: 45
                            }
                        }
                    }
                }
            });
        });
    </script>
    @endif
</div>
