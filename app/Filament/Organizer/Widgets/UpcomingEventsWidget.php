<?php

namespace App\Filament\Organizer\Widgets;

use App\Models\Event;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;

class UpcomingEventsWidget extends TableWidget
{
    protected static ?string $heading = 'Upcoming Events';

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        $eventIds = $user->organizer?->events()->pluck('id') ?? collect();

        return $table
            ->query(
                Event::query()
                    ->whereIn('id', $eventIds)
                    ->where('start_at', '>=', now())
                    ->orderBy('start_at')
                    ->limit(10)
            )
            ->columns([
                TextColumn::make('title')
                    ->searchable()
                    ->limit(35),

                TextColumn::make('category.name')
                    ->label('Category')
                    ->badge(),

                TextColumn::make('city')
                    ->placeholder('—'),

                TextColumn::make('start_at')
                    ->label('Starts')
                    ->dateTime('M d, Y H:i')
                    ->sortable(),

                TextColumn::make('end_at')
                    ->label('Ends')
                    ->dateTime('M d, Y H:i')
                    ->sortable(),

                TextColumn::make('status')
                    ->badge(),

                TextColumn::make('visibility')
                    ->badge(),

                TextColumn::make('orders_count')
                    ->label('Orders')
                    ->counts('orders'),

                TextColumn::make('tickets_count')
                    ->label('Tickets Sold')
                    ->counts('tickets'),
            ])
            ->paginated(false);
    }
}
