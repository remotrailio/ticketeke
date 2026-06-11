<?php

namespace App\Filament\Widgets;

use App\Enums\PaymentStatus;
use App\Models\Event;
use App\Models\Order;
use App\Models\Organizer;
use App\Models\Ticket;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class AdminStatsOverviewWidget extends StatsOverviewWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $revenue = Order::where('payment_status', PaymentStatus::PAID)->sum('total');

        return [
            Stat::make('Total Revenue', 'KES ' . number_format($revenue, 2))
                ->icon('heroicon-o-banknotes')
                ->color('success'),

            Stat::make('Total Orders', number_format(Order::count()))
                ->icon('heroicon-o-shopping-cart')
                ->color('primary'),

            Stat::make('Tickets Issued', number_format(Ticket::count()))
                ->icon('heroicon-o-ticket')
                ->color('info'),

            Stat::make('Events', number_format(Event::count()))
                ->icon('heroicon-o-calendar')
                ->color('warning'),

            Stat::make('Organizers', number_format(Organizer::count()))
                ->icon('heroicon-o-building-office')
                ->color('gray'),

            Stat::make('Users', number_format(User::count()))
                ->icon('heroicon-o-users')
                ->color('gray'),
        ];
    }
}
