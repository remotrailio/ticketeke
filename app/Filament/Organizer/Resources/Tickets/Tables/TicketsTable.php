<?php

namespace App\Filament\Organizer\Resources\Tickets\Tables;

use App\Enums\TicketStatus;
use App\Models\Event;
use App\Models\Ticket;
use Filament\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Select;

class TicketsTable
{
    public static function configure(Table $table, array $eventIds): Table
    {
        return $table
            ->columns([
                TextColumn::make('ticket_code')
                    ->label('Ticket Code')
                    ->searchable()
                    ->copyable()
                    ->fontFamily('mono'),

                TextColumn::make('order.order_number')
                    ->label('Order')
                    ->searchable(),

                TextColumn::make('user.name')
                    ->label('Attendee')
                    ->searchable(),

                TextColumn::make('order.event.title')
                    ->label('Event')
                    ->searchable(),

                TextColumn::make('orderItem.ticketType.name')
                    ->label('Ticket Type'),

                TextColumn::make('status')
                    ->badge()
                    ->color(fn (TicketStatus $state): string => match ($state) {
                        TicketStatus::VALID     => 'success',
                        TicketStatus::USED      => 'gray',
                        TicketStatus::CANCELLED => 'danger',
                    }),

                TextColumn::make('checked_in_at')
                    ->label('Checked In')
                    ->dateTime()
                    ->placeholder('—'),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options(TicketStatus::class),

                Filter::make('event_id')
                    ->label('Event')
                    ->schema([
                        Select::make('event_id')
                            ->label('Event')
                            ->options(
                                Event::query()
                                    ->whereIn('id', $eventIds)
                                    ->pluck('title', 'id')
                            )
                            ->searchable()
                            ->placeholder('All my events'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            $data['event_id'] ?? null,
                            fn (Builder $q, $id) => $q->whereHas(
                                'order',
                                fn (Builder $q) => $q->where('event_id', $id)
                            )
                        );
                    }),
            ])
            ->recordActions([
                Action::make('check_in')
                    ->label('Check In')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->visible(fn (Ticket $record): bool => $record->status === TicketStatus::VALID)
                    ->action(function (Ticket $record): void {
                        $record->checkIn();
                    }),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
