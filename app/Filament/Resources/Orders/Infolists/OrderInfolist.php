<?php

namespace App\Filament\Resources\Orders\Infolists;

use App\Models\Order;
use App\Models\OrderItem;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class OrderInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            TextEntry::make('order_number')
                ->label('Order #')
                ->copyable(),

            TextEntry::make('user.name')
                ->label('Customer'),

            TextEntry::make('event.title')
                ->label('Event'),

            TextEntry::make('status')
                ->badge(),

            TextEntry::make('payment_status')
                ->label('Payment')
                ->badge(),

            TextEntry::make('currency')
                ->badge(),

            TextEntry::make('subtotal')
                ->money(fn (Order $record): string => $record->currency),

            TextEntry::make('fees')
                ->money(fn (Order $record): string => $record->currency),

            TextEntry::make('discount')
                ->money(fn (Order $record): string => $record->currency),

            TextEntry::make('total')
                ->money(fn (Order $record): string => $record->currency),

            TextEntry::make('payment_provider')
                ->placeholder('—'),

            TextEntry::make('payment_reference')
                ->placeholder('—')
                ->copyable(),

            TextEntry::make('payment_method')
                ->placeholder('—'),

            TextEntry::make('expires_at')
                ->dateTime(),

            TextEntry::make('paid_at')
                ->dateTime()
                ->placeholder('—'),

            RepeatableEntry::make('items')
                ->label('Order Items')
                ->schema([
                    TextEntry::make('ticketType.name')
                        ->label('Ticket'),
                    TextEntry::make('quantity'),
                    TextEntry::make('unit_price')
                        ->money(fn (OrderItem $record): string => $record->order->currency),
                    TextEntry::make('subtotal')
                        ->money(fn (OrderItem $record): string => $record->order->currency),
                ]),
        ]);
    }
}
