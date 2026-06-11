<?php

namespace App\Filament\Organizer\Resources\Tickets;

use App\Filament\Organizer\Resources\Tickets\Pages\ListTickets;
use App\Filament\Organizer\Resources\Tickets\Tables\TicketsTable;
use App\Models\Ticket;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class TicketResource extends Resource
{
    protected static ?string $model = Ticket::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTicket;

    protected static ?string $navigationLabel = 'Tickets';

    public static function getEloquentQuery(): Builder
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        $eventIds = $user->organizer?->events()->pluck('id') ?? collect();

        return parent::getEloquentQuery()
            ->whereHas('order', fn (Builder $q) => $q->whereIn('event_id', $eventIds));
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([]);
    }

    public static function table(Table $table): Table
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        $eventIds = $user->organizer?->events()->pluck('id')->toArray() ?? [];

        return TicketsTable::configure($table, $eventIds);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canDelete(\Illuminate\Database\Eloquent\Model $record): bool
    {
        return false;
    }

    public static function getPages(): array
    {
        return [
            'index' => ListTickets::route('/'),
        ];
    }
}
