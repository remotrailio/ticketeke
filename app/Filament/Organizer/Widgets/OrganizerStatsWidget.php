<?php

namespace App\Filament\Organizer\Widgets;

use App\Enums\PaymentStatus;
use App\Models\Event;
use App\Models\Order;
use App\Models\Ticket;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class OrganizerStatsWidget extends StatsOverviewWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        $organizer = $user->organizer;

        if (! $organizer) {
            return [];
        }

        $eventIds = $organizer->events()->pluck('id');

        $currency = strtoupper(
            Order::whereIn('event_id', $eventIds)
                ->where('payment_status', PaymentStatus::PAID)
                ->value('currency') ?? 'KES'
        );

        $totalRevenue = Order::whereIn('event_id', $eventIds)
            ->where('payment_status', PaymentStatus::PAID)
            ->sum('total');

        $totalOrders = Order::whereIn('event_id', $eventIds)
            ->where('payment_status', PaymentStatus::PAID)
            ->count();

        $totalTicketsSold = Ticket::whereHas(
            'order',
            fn ($q) => $q->whereIn('event_id', $eventIds)
                ->where('payment_status', PaymentStatus::PAID)
        )->count();

        $totalEvents = Event::whereIn('id', $eventIds)->count();

        return [
            Stat::make('Total Revenue', $currency . ' ' . number_format($totalRevenue, 2))
                ->description('From paid orders')
                ->icon('heroicon-o-banknotes')
                ->color('success'),

            Stat::make('Paid Orders', number_format($totalOrders))
                ->description('Completed payments')
                ->icon('heroicon-o-shopping-cart')
                ->color('primary'),

            Stat::make('Tickets Sold', number_format($totalTicketsSold))
                ->description('Individual attendees')
                ->icon('heroicon-o-ticket')
                ->color('warning'),

            Stat::make('Events', number_format($totalEvents))
                ->description('All your events')
                ->icon('heroicon-o-calendar')
                ->color('info'),
        ];
    }
}
