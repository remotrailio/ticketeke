<?php

namespace App\Filament\Organizer\Widgets;

use App\Enums\PaymentStatus;
use App\Models\Order;
use Filament\Widgets\ChartWidget;

class OrganizerRevenueChartWidget extends ChartWidget
{
    protected static ?int $sort = 2;

    protected ?string $heading = 'Revenue (Last 30 Days)';

    protected ?string $maxHeight = '300px';

    protected function getType(): string
    {
        return 'line';
    }

    protected function getData(): array
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        $organizer = $user->organizer;

        if (! $organizer) {
            return ['labels' => [], 'datasets' => []];
        }

        $eventIds = $organizer->events()->pluck('id');

        $start = now()->subDays(29)->startOfDay();

        $rows = Order::query()
            ->whereIn('event_id', $eventIds)
            ->where('payment_status', PaymentStatus::PAID)
            ->where('paid_at', '>=', $start)
            ->selectRaw('DATE(paid_at) as date, SUM(total) as revenue')
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('revenue', 'date');

        $labels = [];
        $data   = [];

        for ($i = 29; $i >= 0; $i--) {
            $date     = now()->subDays($i)->format('Y-m-d');
            $labels[] = now()->subDays($i)->format('M d');
            $data[]   = round((float) ($rows[$date] ?? 0), 2);
        }

        return [
            'labels'   => $labels,
            'datasets' => [
                [
                    'label'           => 'Revenue (KES)',
                    'data'            => $data,
                    'borderColor'     => '#f59e0b',
                    'backgroundColor' => 'rgba(245, 158, 11, 0.1)',
                    'fill'            => true,
                    'tension'         => 0.4,
                ],
            ],
        ];
    }
}
