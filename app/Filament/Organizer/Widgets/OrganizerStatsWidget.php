<?php

namespace App\Filament\Organizer\Widgets;

use App\Enums\OrderStatus;
use App\Models\Event;
use App\Models\Order;
use App\Models\Ticket;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class OrganizerStatsWidget extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        $organizer = $user->organizer;

        if (! $organizer) {
            return [];
        }

        $eventIds = $organizer->events()->pluck('id');

        $totalEvents = Event::whereIn('id', $eventIds)->count();

        $totalOrders = Order::whereIn('event_id', $eventIds)
            ->where('status', OrderStatus::COMPLETED)
            ->count();

        $totalTicketsSold = Ticket::whereHas(
            'order',
            fn ($q) => $q->whereIn('event_id', $eventIds)
        )->count();

        return [
            Stat::make('Total Events', $totalEvents)
                ->icon('heroicon-o-calendar'),

            Stat::make('Completed Orders', $totalOrders)
                ->icon('heroicon-o-shopping-cart'),

            Stat::make('Tickets Sold', $totalTicketsSold)
                ->icon('heroicon-o-ticket'),
        ];
    }
}
