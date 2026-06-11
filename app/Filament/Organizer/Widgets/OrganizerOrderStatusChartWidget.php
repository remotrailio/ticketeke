<?php

namespace App\Filament\Organizer\Widgets;

use App\Enums\PaymentStatus;
use App\Models\Order;
use Filament\Widgets\ChartWidget;

class OrganizerOrderStatusChartWidget extends ChartWidget
{
    protected static ?int $sort = 3;

    protected ?string $heading = 'Orders by Payment Status';

    protected ?string $maxHeight = '280px';

    protected function getType(): string
    {
        return 'doughnut';
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

        $counts = Order::whereIn('event_id', $eventIds)
            ->selectRaw('payment_status, COUNT(*) as total')
            ->groupBy('payment_status')
            ->pluck('total', 'payment_status');

        $statuses = [
            PaymentStatus::PAID->value       => ['label' => 'Paid',       'color' => '#22c55e'],
            PaymentStatus::UNPAID->value     => ['label' => 'Unpaid',     'color' => '#94a3b8'],
            PaymentStatus::PROCESSING->value => ['label' => 'Processing', 'color' => '#f59e0b'],
            PaymentStatus::FAILED->value     => ['label' => 'Failed',     'color' => '#ef4444'],
            PaymentStatus::REFUNDED->value   => ['label' => 'Refunded',   'color' => '#a855f7'],
        ];

        $labels = [];
        $data   = [];
        $colors = [];

        foreach ($statuses as $value => $meta) {
            $count = (int) ($counts[$value] ?? 0);

            if ($count === 0) {
                continue;
            }

            $labels[] = $meta['label'] . ' (' . number_format($count) . ')';
            $data[]   = $count;
            $colors[]  = $meta['color'];
        }

        return [
            'labels'   => $labels,
            'datasets' => [
                [
                    'data'                 => $data,
                    'backgroundColor'      => $colors,
                    'borderColor'          => $colors,
                    'borderWidth'          => 2,
                    'hoverOffset'          => 6,
                ],
            ],
        ];
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'position' => 'bottom',
                ],
            ],
            'cutout' => '65%',
        ];
    }
}
