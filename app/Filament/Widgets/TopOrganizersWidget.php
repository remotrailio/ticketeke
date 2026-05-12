<?php

namespace App\Filament\Widgets;

use App\Models\Organizer;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class TopOrganizersWidget extends TableWidget
{
    protected static ?int $sort = 5;

    protected static ?string $heading = 'Top Organizers';

    protected int|string|array $columnSpan = 1;

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Organizer::query()
                    ->select('organizers.*')
                    ->selectRaw('COUNT(DISTINCT events.id) as events_count')
                    ->selectRaw('COUNT(tickets.id) as tickets_count')
                    ->selectRaw(
                        'COALESCE(SUM(CASE WHEN orders.payment_status = ? THEN orders.total ELSE 0 END), 0) as revenue',
                        ['paid']
                    )
                    ->leftJoin('events', 'events.organizer_id', '=', 'organizers.id')
                    ->leftJoin('orders', 'orders.event_id', '=', 'events.id')
                    ->leftJoin('tickets', 'tickets.order_id', '=', 'orders.id')
                    ->groupBy('organizers.id')
                    ->orderByDesc('revenue')
                    ->limit(10)
            )
            ->columns([
                TextColumn::make('display_name')
                    ->label('Organizer')
                    ->searchable(),

                TextColumn::make('events_count')
                    ->label('Events')
                    ->state(fn (Organizer $record): int => (int) $record->events_count)
                    ->alignCenter(),

                TextColumn::make('tickets_count')
                    ->label('Tickets Sold')
                    ->state(fn (Organizer $record): int => (int) $record->tickets_count)
                    ->alignCenter(),

                TextColumn::make('revenue')
                    ->label('Revenue')
                    ->state(fn (Organizer $record): string => 'KES ' . number_format((float) $record->revenue, 2))
                    ->alignEnd(),
            ])
            ->paginated(false);
    }
}
