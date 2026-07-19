@php
    $campaign = $record ?? $getRecord();
    $recipients = $campaign->campaignRecipients()->with('recipient')->get();

    $recipientStats = [
        'total' => $recipients->count(),
        'pending' => $recipients->where('mailganer_status', 'pending')->count(),
        'accepted' => $recipients->where('mailganer_status', 'accepted')->count(),
        'delivered' => $recipients->where('mailganer_status', 'delivered')->count(),
        'opened' => $recipients->where('mailganer_status', 'open')->count(),
        'clicked' => $recipients->where('mailganer_status', 'click')->count(),
        'failed' => $recipients->where('mailganer_status', 'failed')->count(),
        'fbl' => $recipients->where('mailganer_status', 'fbl')->count(),
        'unsubscribe' => $recipients->where('mailganer_status', 'unsubscribe')->count(),
        'duplicate' => $recipients->where('mailganer_status', 'duplicate')->count(),
    ];

    $recentRecipients = $recipients->take(10)->map(function ($recipient) {
        return [
            'email' => $recipient->recipient->email ?? 'N/A',
            'status' => $recipient->mailganer_status,
            'reason' => $recipient->mailganer_reason,
            'click_link' => $recipient->mailganer_click_link,
        ];
    })->toArray();
@endphp

<div class="space-y-4">
    <!-- Summary Stats -->
    <div class="grid grid-cols-3 gap-4">
        <div class="text-center p-3 bg-gray-50 dark:bg-gray-800 rounded-lg">
            <div class="text-2xl font-bold">{{ $recipientStats['total'] }}</div>
            <div class="text-sm text-gray-500">Всего</div>
        </div>
        <div class="text-center p-3 bg-green-50 dark:bg-green-900/20 rounded-lg">
            <div class="text-2xl font-bold text-green-600">{{ $recipientStats['delivered'] }}</div>
            <div class="text-sm text-gray-500">Доставлено</div>
        </div>
        <div class="text-center p-3 bg-purple-50 dark:bg-purple-900/20 rounded-lg">
            <div class="text-2xl font-bold text-purple-600">{{ $recipientStats['opened'] }}</div>
            <div class="text-sm text-gray-500">Открыто</div>
        </div>
    </div>

    <!-- Recent Recipients Table -->
    <div>
        <h4 class="font-medium mb-2">Последние получатели</h4>
        <div class="border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 dark:bg-gray-800">
                    <tr>
                        <th class="px-4 py-2 text-left">Email</th>
                        <th class="px-4 py-2 text-left">Статус</th>
                        <th class="px-4 py-2 text-left">Детали</th>
                    </tr>
                </thead>
                <tbody>

                    @foreach($recentRecipients as $recipient)
                        <tr class="border-t border-gray-200 dark:border-gray-700">
                            <td class="px-4 py-2">{{ $recipient['email'] }}</td>
                            <td class="px-4 py-2">
                                @php
                                    $statusColors = [
                                        'pending' => 'bg-gray-100 text-gray-800',
                                        'accepted' => 'bg-blue-100 text-blue-800',
                                        'delivered' => 'bg-green-100 text-green-800',
                                        'open' => 'bg-purple-100 text-purple-800',
                                        'click' => 'bg-orange-100 text-orange-800',
                                        'failed' => 'bg-red-100 text-red-800',
                                        'fbl' => 'bg-red-200 text-red-900',
                                        'unsubscribe' => 'bg-gray-200 text-gray-700',
                                        'duplicate' => 'bg-yellow-100 text-yellow-800',
                                    ];
                                    $statusLabels = [
                                        'pending' => 'В ожидании',
                                        'accepted' => 'Принято',
                                        'delivered' => 'Доставлено',
                                        'open' => 'Открыто',
                                        'click' => 'Клик',
                                        'failed' => 'Ошибка',
                                        'fbl' => 'Жалоба',
                                        'unsubscribe' => 'Отписался',
                                        'duplicate' => 'Дубль',
                                    ];
                                @endphp
                                <span class="px-2 py-1 rounded-full text-xs {{ $statusColors[$recipient['status']] ?? 'bg-gray-100 text-gray-800' }}">
                                    {{ $statusLabels[$recipient['status']] ?? $recipient['status'] }}
                                </span>
                            </td>
                            <td class="px-4 py-2 text-gray-500 text-xs">
                                @if($recipient['reason'])
                                    <div class="text-red-600">{{ $recipient['reason'] }}</div>
                                @endif
                                @if($recipient['click_link'])
                                    <div class="text-blue-600 truncate max-w-xs">{{ $recipient['click_link'] }}</div>
                                @endif
                                @if(!$recipient['reason'] && !$recipient['click_link'])
                                    -
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
