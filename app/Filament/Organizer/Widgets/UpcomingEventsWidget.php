<?php

namespace App\Filament\Organizer\Widgets;

use App\Models\Event;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

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
                    ->limit(5)
            )
            ->columns([
                TextColumn::make('title')
                    ->searchable(),

                TextColumn::make('start_at')
                    ->label('Starts')
                    ->dateTime()
                    ->sortable(),

                TextColumn::make('status')
                    ->badge(),

                TextColumn::make('ticketTypes_count')
                    ->label('Ticket Types')
                    ->counts('ticketTypes'),
            ])
            ->paginated(false);
    }
}
