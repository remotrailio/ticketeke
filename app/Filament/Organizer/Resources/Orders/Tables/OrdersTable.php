<?php

namespace App\Filament\Organizer\Resources\Orders\Tables;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Models\Order;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class OrdersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('order_number')
                    ->label('Order #')
                    ->searchable()
                    ->copyable(),
                TextColumn::make('user.name')
                    ->label('Customer')
                    ->searchable(),
                TextColumn::make('event.title')
                    ->label('Event')
                    ->searchable(),
                TextColumn::make('status')
                    ->badge(),
                TextColumn::make('payment_status')
                    ->label('Payment')
                    ->badge(),
                TextColumn::make('total')
                    ->money(fn (Order $record): string => $record->currency)
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        OrderStatus::PENDING->value   => 'Pending',
                        OrderStatus::COMPLETED->value => 'Completed',
                        OrderStatus::CANCELLED->value => 'Cancelled',
                        OrderStatus::EXPIRED->value   => 'Expired',
                        OrderStatus::REFUNDED->value  => 'Refunded',
                    ]),
                SelectFilter::make('payment_status')
                    ->label('Payment Status')
                    ->options([
                        PaymentStatus::UNPAID->value     => 'Unpaid',
                        PaymentStatus::PROCESSING->value => 'Processing',
                        PaymentStatus::PAID->value       => 'Paid',
                        PaymentStatus::FAILED->value     => 'Failed',
                        PaymentStatus::REFUNDED->value   => 'Refunded',
                    ]),
            ])
            ->recordActions([
                ViewAction::make(),
            ]);
    }
}
