<?php

namespace App\Filament\Organizer\Resources\TicketTypes;

use App\Filament\Organizer\Resources\Events\EventResource;
use App\Filament\Organizer\Resources\TicketTypes\Pages\CreateTicketType;
use App\Filament\Organizer\Resources\TicketTypes\Pages\EditTicketType;
use App\Filament\Organizer\Resources\TicketTypes\Pages\ListTicketTypes;
use App\Filament\Organizer\Resources\TicketTypes\Schemas\TicketTypeForm;
use App\Filament\Organizer\Resources\TicketTypes\Tables\TicketTypesTable;
use App\Models\TicketType;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class TicketTypeResource extends Resource
{
    protected static ?string $model = TicketType::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTicket;

    protected static ?string $parentResource = EventResource::class;

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
