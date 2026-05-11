<?php

namespace App\Filament\Resources\Events;

use App\Enums\UserRole;
use App\Filament\Resources\Events\Pages\CreateEvent;
use App\Filament\Resources\Events\Pages\EditEvent;
use App\Filament\Resources\Events\Pages\ListEvents;
use App\Filament\Resources\Events\Schemas\EventForm;
use App\Filament\Resources\Events\Tables\EventsTable;
use App\Models\Event;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class EventResource extends Resource
{
    protected static ?string $model = Event::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCalendarDays;

    public static function getEloquentQuery(): Builder
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        if ($user->role === UserRole::ADMIN) {
            return parent::getEloquentQuery();
        }

        return parent::getEloquentQuery()
            ->where('organizer_id', $user->organizer?->id ?? 0);
    }

    public static function form(Schema $schema): Schema
    {
        return EventForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return EventsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListEvents::route('/'),
            'create' => CreateEvent::route('/create'),
            'edit'   => EditEvent::route('/{record}/edit'),
        ];
    }
}
