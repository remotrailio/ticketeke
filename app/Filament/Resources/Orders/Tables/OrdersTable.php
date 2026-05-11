<?php

namespace App\Filament\Resources\Orders\Tables;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Models\Order;
use App\Models\Organizer;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

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
                TextColumn::make('expires_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('paid_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
                SelectFilter::make('event_id')
                    ->relationship('event', 'title')
                    ->label('Event')
                    ->searchable(),
                Filter::make('organizer_id')
                    ->label('Organizer')
                    ->form([
                        Select::make('organizer_id')
                            ->label('Organizer')
                            ->options(Organizer::query()->pluck('display_name', 'id')->all())
                            ->searchable(),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            $data['organizer_id'] ?? null,
                            fn ($q, $id) => $q->whereHas(
                                'event',
                                fn ($q) => $q->where('organizer_id', $id)
                            )
                        );
                    }),
            ])
            ->recordActions([
                ViewAction::make(),
            ]);
    }
}
