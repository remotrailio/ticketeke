<?php

namespace App\Filament\Widgets;

use App\Enums\EventStatus;
use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Models\Event;
use App\Models\Order;
use App\Models\Organizer;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class OperationalAlertsWidget extends StatsOverviewWidget
{
    protected static ?int $sort = 2;

    protected function getStats(): array
    {
        $unverifiedOrganizers = Organizer::where('verified', false)->count();
        $failedPayments       = Order::where('payment_status', PaymentStatus::FAILED)->count();
        $expiredOrders        = Order::where('status', OrderStatus::EXPIRED)->count();
        $draftEvents          = Event::where('status', EventStatus::DRAFT)->count();

        return [
            Stat::make('Unverified Organizers', $unverifiedOrganizers)
                ->icon('heroicon-o-shield-exclamation')
                ->color($unverifiedOrganizers > 0 ? 'warning' : 'success'),

            Stat::make('Failed Payments', $failedPayments)
                ->icon('heroicon-o-x-circle')
                ->color($failedPayments > 0 ? 'danger' : 'success'),

            Stat::make('Expired Orders', $expiredOrders)
                ->icon('heroicon-o-clock')
                ->color($expiredOrders > 0 ? 'warning' : 'success'),

            Stat::make('Draft Events', $draftEvents)
                ->icon('heroicon-o-pencil-square')
                ->color('gray'),
        ];
    }
}
