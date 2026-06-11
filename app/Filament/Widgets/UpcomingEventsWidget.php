<?php

namespace App\Filament\Widgets;

use App\Models\Event;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;

class UpcomingEventsWidget extends TableWidget
{
    protected static ?int $sort = 6;

    protected static ?string $heading = 'Upcoming Events';

    protected int|string|array $columnSpan = 1;

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Event::query()
                    ->with('organizer')
                    ->where('start_at', '>=', now())
                    ->orderBy('start_at')
                    ->limit(10)
            )
            ->columns([
                TextColumn::make('title')
                    ->searchable()
                    ->limit(30),

                TextColumn::make('organizer.display_name')
                    ->label('Organizer'),

                TextColumn::make('start_at')
                    ->label('Starts')
                    ->dateTime('M d, Y H:i')
                    ->sortable(),

                TextColumn::make('status')
                    ->badge(),
            ])
            ->paginated(false);
    }
}
