<?php

namespace App\Filament\Resources\Orders;

use App\Enums\UserRole;
use App\Filament\Resources\Orders\Infolists\OrderInfolist;
use App\Filament\Resources\Orders\Pages\ListOrders;
use App\Filament\Resources\Orders\Pages\ViewOrder;
use App\Filament\Resources\Orders\Tables\OrdersTable;
use App\Models\Order;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedShoppingCart;

    public static function getEloquentQuery(): Builder
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        if ($user->role === UserRole::ADMIN) {
            return parent::getEloquentQuery();
        }

        // Safety: scope to organizer's events if a non-admin reaches admin panel
        $eventIds = $user->organizer?->events()->pluck('id') ?? collect();

        return parent::getEloquentQuery()
            ->whereIn('event_id', $eventIds);
    }

    public static function infolist(Schema $schema): Schema
    {
        return OrderInfolist::configure($schema);
    }

    public static function form(Schema $schema): Schema
    {
        // Orders are not created or edited via Filament; return empty schema
        return $schema->components([]);
    }

    public static function table(Table $table): Table
    {
        return OrdersTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListOrders::route('/'),
            'view'  => ViewOrder::route('/{record}'),
        ];
    }
}
