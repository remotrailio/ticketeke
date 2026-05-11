<?php

namespace App\Filament\Resources\TicketTypes;

use App\Enums\UserRole;
use App\Filament\Resources\TicketTypes\Pages\CreateTicketType;
use App\Filament\Resources\TicketTypes\Pages\EditTicketType;
use App\Filament\Resources\TicketTypes\Pages\ListTicketTypes;
use App\Filament\Resources\TicketTypes\Schemas\TicketTypeForm;
use App\Filament\Resources\TicketTypes\Tables\TicketTypesTable;
use App\Models\TicketType;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class TicketTypeResource extends Resource
{
    protected static ?string $model = TicketType::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTicket;

    public static function getEloquentQuery(): Builder
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        if ($user->role === UserRole::ADMIN) {
            return parent::getEloquentQuery();
        }

        $eventIds = $user->organizer?->events()->pluck('id') ?? collect();

        return parent::getEloquentQuery()
            ->whereIn('event_id', $eventIds);
    }

    public static function form(Schema $schema): Schema
    {
        return TicketTypeForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TicketTypesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListTicketTypes::route('/'),
            'create' => CreateTicketType::route('/create'),
            'edit'   => EditTicketType::route('/{record}/edit'),
        ];
    }
}
