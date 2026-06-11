<?php

namespace App\Filament\Widgets;

use App\Enums\PaymentStatus;
use App\Models\Order;
use Filament\Widgets\ChartWidget;

class RevenueChartWidget extends ChartWidget
{
    protected static ?int $sort = 3;

    protected ?string $heading = 'Revenue (Last 30 Days)';

    protected ?string $maxHeight = '300px';

    protected int|string|array $columnSpan = 1;

    protected function getType(): string
    {
        return 'line';
    }

    protected function getData(): array
    {
        $start = now()->subDays(29)->startOfDay();

        $rows = Order::query()
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
                    'borderColor'     => '#2563EB',
                    'backgroundColor' => 'rgba(37, 99, 235, 0.1)',
                    'fill'            => true,
                    'tension'         => 0.4,
                ],
            ],
        ];
    }
}
