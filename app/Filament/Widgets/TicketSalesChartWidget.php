<?php

namespace App\Filament\Widgets;

use App\Models\Ticket;
use Filament\Widgets\ChartWidget;

class TicketSalesChartWidget extends ChartWidget
{
    protected static ?int $sort = 4;

    protected ?string $heading = 'Tickets Issued (Last 30 Days)';

    protected ?string $maxHeight = '300px';

    protected int|string|array $columnSpan = 1;

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getData(): array
    {
        $start = now()->subDays(29)->startOfDay();

        $rows = Ticket::query()
            ->where('created_at', '>=', $start)
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('count', 'date');

        $labels = [];
        $data   = [];

        for ($i = 29; $i >= 0; $i--) {
            $date     = now()->subDays($i)->format('Y-m-d');
            $labels[] = now()->subDays($i)->format('M d');
            $data[]   = (int) ($rows[$date] ?? 0);
        }

        return [
            'labels'   => $labels,
            'datasets' => [
                [
                    'label'           => 'Tickets Issued',
                    'data'            => $data,
                    'backgroundColor' => 'rgba(245, 158, 11, 0.7)',
                    'borderColor'     => '#f59e0b',
                    'borderWidth'     => 1,
                ],
            ],
        ];
    }
}
